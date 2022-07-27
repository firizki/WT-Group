<?php
require '../vendor/autoload.php';
require 'db.php';

$app = new \Slim\App();

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

//create donations
$app->post('/donation', function ($request, $response, $args) {
    $id = $_POST['id'];
    $created = $_POST['created'];
    $accepted = $_POST['accepted'];
    $canceled = $_POST['canceled'];
    $state = $_POST['state'];
    $donator_id = $_POST['donator_id'];
    $event_id = $_POST['event_id'];
    try {
        $sql = "INSERT INTO donations (id,created,accepted,canceled,state,donator_id,event_id) VALUES (:id,:created,:accepted,:canceled,:state,:donator_id,:event_id)";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
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
        echo json_encode($data);
    }
});

//read all donations
$app->get('/donations', function ($request, $response, $args) {
    $sql = "SELECT * FROM donations";
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

//create food
$app->post('/food', function ($request, $response, $args) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $donation_id = $_POST['donation_id'];
    try {
        $sql = "INSERT INTO foods (id,name,quantity,donation_id) VALUES (:id,:name,:quantity,:donation_id)";
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
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
        echo json_encode($data);
    }
});

//read all food
$app->get('/foods', function ($request, $response, $args) {
    $sql = "SELECT * FROM foods";
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