<?php
require '../vendor/autoload.php';
require 'db.php';
// require 'login.php';

$app = new \Slim\App();

$app->get('/login', function ($request, $response, $args) {
    $email = $_GET["email"];
    $password = $_GET["password"];
    $sql = "SELECT * FROM donators WHERE email = '$email' AND password = '$password'";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
    
        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

$app->get('/active_events', function ($request, $response, $args) {
    $sql = "SELECT * FROM events WHERE active=1";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

$app->get('/past_events', function ($request, $response, $args) {
    $sql = "SELECT * FROM events WHERE active=0";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

$app->post('/donation', function ($request, $response, $args) {
    $created = date("Y-m-d h:i:s");
    $state = 0;
    $donator_id = $_POST['donator_id'];
    $event_id = $_POST['event_id'];
    try {
        $sql = "INSERT INTO donations (created,state,donator_id,event_id) VALUES (:created,:state,:donator_id,:event_id)";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':created', $created);
        $stmt->bindValue(':state', $state);
        $stmt->bindValue(':donator_id', $donator_id);
        $stmt->bindValue(':event_id', $event_id);

        $stmt->execute();
        $last_id = $db->lastInsertId();
        $count = $stmt->rowCount();
        $db = null;

        $data = array(
            "id" => $last_id,
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

$app->post('/foods', function ($request, $response, $args) {
    $data = json_decode($_POST['data'], true);
    $donation_id = $_POST['donation_id'];
    try {
        $sql = "INSERT INTO foods (name,quantity,donation_id) VALUES ";
        foreach ($data as $d) {
            $tmp_sql = "('".$d["name"]."',".$d["quantity"].",".$donation_id."),";
            $sql .= $tmp_sql;
        }
        $sql = substr($sql, 0, -1);
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;

        $data = array(
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

$app->get('/foods', function ($request, $response, $args) {
    $donation_id = $_GET['donation_id'];
    $sql = "SELECT * FROM foods WHERE donation_id=".$donation_id;
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

$app->get('/donations', function ($request, $response, $args) {
    $donator_id = $_GET['donator_id'];
    $sql = "SELECT * FROM donations WHERE donator_id=".$donator_id;
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

//read one event
$app->get('/event/{id}', function ($request, $response, $args) {
    $id = $args["id"];
    $sql = "SELECT * FROM events WHERE id = '$id'";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
    
        $stmt = $db->query($sql);
        $event = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($event[0]);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

$app->get('/admin_login', function ($request, $response, $args) {
    $username = $_GET["username"];
    $password = $_GET["password"];
    $sql = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
    
        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

$app->post('/change_event_state', function ($request, $response, $args) {
    $id = $_POST['event_id'];
    $active = $_POST['active'];

    try {
        $sql = "UPDATE events SET active=:active WHERE id='$id'";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':active', $active);

        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;

        $data = array(
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

$app->get('/admin_donations', function ($request, $response, $args) {
    $state = $_GET['state'];
    if ($state == null) {
        $sql = "SELECT * FROM donations ";
    } else {
        $sql = "SELECT * FROM donations WHERE state='$state'";
    }
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

$app->post('/accept_donation', function ($request, $response, $args) {
    $id = $_POST['donation_id'];
    $accepted = date("Y-m-d h:i:s");
    $state = 1;

    try {
        $sql = "UPDATE donations SET accepted=:accepted, state=:state WHERE id='$id'";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':accepted', $accepted);
        $stmt->bindValue(':state', $state);

        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;

        $data = array(
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

$app->post('/reject_donation', function ($request, $response, $args) {
    $id = $_POST['donation_id'];
    $canceled = date("Y-m-d h:i:s");
    $state = 2;

    try {
        $sql = "UPDATE donations SET canceled=:canceled, state=:state WHERE id='$id'";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':canceled', $canceled);
        $stmt->bindValue(':state', $state);

        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;

        $data = array(
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

/////////////////////////////////////////////////////////////////////////////////////////////////////////

//create admin
$app->post('/admin', function ($request, $response, $args) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    try {
        $sql = "INSERT INTO admins (id,name,username,password) VALUES (:id,:name,:username,:password)";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $password);

        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;

        $data = array(
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

//read all admin
$app->get('/admins', function ($request, $response, $args) {
    $sql = "SELECT * FROM admins";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

//read one admin
$app->get('/admin/{id}', function ($request, $response, $args) {
    $id = $args["id"];
    $sql = "SELECT * FROM admins WHERE id = '$id'";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
    
        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

//update admin
$app->post('/admin/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $sql = "UPDATE admins SET name=:name, username=:username, password=:password WHERE id='$id'";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $password);

        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;

        $data = array(
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

//delete admin
$app->delete('/admin/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $sql = "delete from admins where id = '$id'";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;
        $data = array(
            "rowAffected" => $count,
            "status" => "success"
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

//read one donation
$app->get('/donation/{id}', function ($request, $response, $args) {
    $id = $args["id"];
    $sql = "SELECT * FROM donations WHERE id = '$id'";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
    
        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

//update donation
$app->post('/donation/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $created = $_POST['created'];
    $accepted = $_POST['accepted'];
    $canceled = $_POST['canceled'];
    $state = $_POST['state'];
    $donator_id = $_POST['donator_id'];
    $event_id = $_POST['event_id'];

    try {
        $sql = "UPDATE donations SET created=:created, accepted=:accepted, canceled=:canceled, state=:state, donator_id=:donator_id, event_id=:event_id WHERE id='$id'";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':created', $created);
        $stmt->bindValue(':accepted', $accepted);
        $stmt->bindValue(':canceled', $canceled);
        $stmt->bindValue(':state', $state);
        $stmt->bindValue(':donator_id', $donator_id);
        $stmt->bindValue(':event_id', $event_id);

        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;

        $data = array(
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

//delete donation
$app->delete('/donation/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $sql = "delete from donations where id = '$id'";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;
        $data = array(
            "rowAffected" => $count,
            "status" => "success"
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});


//create donators
$app->post('/donator', function ($request, $response, $args) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    try {
        $sql = "INSERT INTO donators (id,name,address,phone,email,password) VALUES (:id,:name,:address,:phone,:email,:password)";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $password);

        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;

        $data = array(
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

//read all donators
$app->get('/donators', function ($request, $response, $args) {
    $sql = "SELECT * FROM donators";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

//read one donator
$app->get('/donator/{id}', function ($request, $response, $args) {
    $id = $args["id"];
    $sql = "SELECT * FROM donators WHERE id = '$id'";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
    
        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

//update donator
$app->post('/donator/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $sql = "UPDATE donators SET name=:name, address=:address, phone=:phone, email=:email, password=:password WHERE id='$id'";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $password);

        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;

        $data = array(
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

//delete donator
$app->delete('/donator/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $sql = "delete from donators where id = '$id'";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;
        $data = array(
            "rowAffected" => $count,
            "status" => "success"
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

//create events
$app->post('/event', function ($request, $response, $args) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $address = $_POST['address'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $active = $_POST['active'];
    try {
        $sql = "INSERT INTO events (id,title,address,date,description,active) VALUES (:id,:title,:address,:date,:description,:active)";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':date', $date);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':active', $active);

        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;

        $data = array(
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

//read all events
$app->get('/events', function ($request, $response, $args) {
    $sql = "SELECT * FROM events";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

//update event 
$app->post('/event/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $title = $_POST['title'];
    $address = $_POST['address'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $active = $_POST['active'];

    try {
        $sql = "UPDATE events SET title=:title, address=:address, date=:date, description=:description, active=:active WHERE id='$id'";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':date', $date);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':active', $active);

        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;

        $data = array(
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

//delete event
$app->delete('/event/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $sql = "delete from events where id = '$id'";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;
        $data = array(
            "rowAffected" => $count,
            "status" => "success"
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

//read all food

//read one food
$app->get('/food/{id}', function ($request, $response, $args) {
    $id = $args["id"];
    $sql = "SELECT * FROM foods WHERE id = '$id'";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
    
        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

//update food 
$app->post('/food/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $donation_id = $_POST['donation_id'];
    try {
        $sql = "UPDATE foods SET name=:name, quantity=:quantity, donation_id=:donation_id WHERE id='$id'";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':quantity', $quantity);
        $stmt->bindValue(':donation_id', $donation_id);

        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;

        $data = array(
            "status" => "success",
            "rowcount" =>$count
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo $e;
        echo json_encode($data);
    }
});

//delete food
$app->delete('/food/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $sql = "delete from foods where id = '$id'";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $count = $stmt->rowCount();
        $db = null;
        $data = array(
            "rowAffected" => $count,
            "status" => "success"
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        $data = array(
            "status" => "fail"
        );
        echo json_encode($data);
    }
});

// Run app
$app->run();