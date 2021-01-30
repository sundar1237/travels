<?php

function exportTenant($id){
    
    
}

function showFormToUpdateTenant($id)
{
    $tenants = getFetchArray("select * from tenants where id = " . $id);
    return '<form method="post" action="tenant.php">
    <input type="hidden" name="action" value="update_tenant">
    <input type="hidden" name="id" value="' . $tenants[0]['id'] . '">
        
First Name  <input type="text" value="' . $tenants[0]['first_name'] . " " . $tenants[0]['last_name'] . '" class="form-control" name="first_name">
<br>
Last Name  <input type="text" value="' . $tenants[0]['last_name'] . '" class="form-control" name="last_name">
<br>
Mobile No 1 <input type="text" value="' . $tenants[0]['mobile_no_1'] . '" class="form-control" name="mobile_no_1">
<br>
Mobile No 2 <input type="text" value="' . $tenants[0]['mobile_no_2'] . '" class="form-control" name="mobile_no_2">
<br>
    
Occupation <input type="text" value="' . $tenants[0]['occupation'] . '" class="form-control" name="occupation">
<br>
    
Aadhar Card <input type="text" value="' . $tenants[0]['aadhar_card_no'] . '" class="form-control" name="aadhar_card_no">
<br>
    
Comments <textarea class="form-control" name="comments">' . $tenants[0]['comments'] . '</textarea>
<br>
    
</form>';
}

function updateTenant($id){
    
    $first_name=$_POST['first_name'];
    $last_name=$_POST['last_name'];
    $mobile_no_1=$_POST['mobile_no_1'];
    $mobile_no_2=$_POST['mobile_no_2'];
    $occupation=$_POST['occupation'];
    $aadhar_card_no=$_POST['aadhar_card_no'];
    $comments=$_POST['comments'];
    executeSQL("update tenants set first_name=".cheSNull($first_name).", last_name=".cheSNull($last_name).", mobile_no_1=".cheSNull($mobile_no_1).", mobile_no_2= ".cheSNull($mobile_no_2).
        ", occupation=".cheSNull($occupation).", aadhar_card_no=".cheSNull($aadhar_card_no).", comments=".cheSNull($comments)." where id = ".$id);
    
}

function addTenant($id){
    $occupied_since=$_POST['occupied_since'];
    $first_name=$_POST['first_name'];
    $last_name=$_POST['last_name'];
    $mobile_no_1=$_POST['mobile_no_1'];
    $mobile_no_2=$_POST['mobile_no_2'];
    $occupation=$_POST['occupation'];
    $aadhar_card_no=$_POST['aadhaar_no'];
    $comments=$_POST['comments'];
    $sql="INSERT INTO tenants(first_name, last_name, mobile_no_1, mobile_no_2, pending_amount, occupied_since, occupation, notes, aadhar_card_no, apartment_id, comments, img_path, lag_percent) 
                    VALUES(".cheSNull($first_name).",".cheSNull($last_name).",".cheSNull($mobile_no_1).",".cheSNull($mobile_no_2).",0,".cheSNull($occupied_since).",".cheSNull($occupation).",NULL,".cheSNull($aadhar_card_no).",".$id.",".cheSNull($comments).",'images/tenants/person.jpg',0)";
    
    executeSQL($sql);
    $tenant_id=getSingleValue("SELECT max(id) id FROM `tenants`");
    $updateSQL = "UPDATE apartments SET status='Occupied' WHERE id=".$id;
    executeSQL($updateSQL);
    return $tenant_id;
}

/* function uploadDocument($id){
    $target_dir = "./images/tenants/";
    $fileTmpPath = $_FILES['fileToUpload']['tmp_name'];
    $onlyFilename=$_FILES["fileToUpload"]["name"];
    $target_file = $target_dir . basename($onlyFilename);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        move_uploaded_file($fileTmpPath, $target_file);
        $sql="update tenants set img_path='/tenants/".$onlyFilename."' where id=".$id;
        executeSQL($sql);
        $uploadOk = 1;
    } else {
        //echo "File is not an image.";
        $uploadOk = 0;
    }
}


function addDocument($id,$table){
    $target_dir = "./images/documents/";
    
} */

function upload($id, $table, $target_dir,$description){
    $sql=null;
    $fileTmpPath = $_FILES['fileToUpload']['tmp_name'];
    $onlyFilename=$_FILES["fileToUpload"]["name"];
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($onlyFilename);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check != false) {
        //echo "File is an image - " . $check["mime"] . ".";
        move_uploaded_file($fileTmpPath, $target_file);
        if($table==null){
            $sql="update tenants set img_path='".$target_dir.$onlyFilename."' where id=".$id;
        }else{
            $sql="INSERT INTO documents(parent_id, parent_table, img_path, description) VALUES (".$id.",'".$table."','".$target_dir.$onlyFilename."',".cheSNull($description).")";
        }
        executeSQL($sql);
        $uploadOk = 1;
    } else {
        //echo "File is not an image.";
        $uploadOk = 0;
    }
    return $uploadOk;
}

function deleteDocument($id){
    $doc=getFetchArray("select * from documents where id=".$id);
    $img_path=$doc[0]['img_path'];
    if (file_exists($img_path)) {
        unlink($img_path);
    }
    executeSQL("delete from documents where id=".$id);
    return $doc[0]['parent_id'];
}
