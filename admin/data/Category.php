<?php 

// Get All 
// function getAll($conn){
//    $sql = "SELECT * FROM category";
//    $stmt = $conn->prepare($sql);
//    $stmt->execute();

//    if($stmt->rowCount() >= 1){
//    	   $data = $stmt->fetchAll();
//    	   return $data;
//    }else {
//    	 return 0;
//    }
// }

// improved get all function with returning empty array if no data found
function getAll($conn){
   $sql = "SELECT * FROM category";
   $stmt = $conn->prepare($sql);
   $stmt->execute();

   return $stmt->fetchAll(PDO::FETCH_ASSOC); // always array
}

// getById
function getById($conn, $id){
   $sql = "SELECT * FROM category WHERE id=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$id]);

   if($stmt->rowCount() >= 1){
         $data = $stmt->fetch();
         return $data;
   }else {
       return 0;
   }
}

// Delete By ID
function deleteById($conn, $id){
   $sql = "DELETE FROM category WHERE id=?";
   $stmt = $conn->prepare($sql);
   $res = $stmt->execute([$id]);

   if($res){
   	   return 1;
   }else {
   	 return 0;
   }
}