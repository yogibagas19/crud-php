<?php
require 'connection.php';

if (isset($_GET["delete"])) {
    $id = $_GET["delete"];

    // Ambil nama file gambar berdasarkan id yang akan dihapus
    $getImageQuery = "SELECT image FROM upload WHERE id = $id";
    $result = mysqli_query($conn, $getImageQuery);
    $row = mysqli_fetch_assoc($result);
    $imageName = $row["image"];

    // Hapus data dari database
    $queryDelete = "DELETE FROM upload WHERE id = $id";
    mysqli_query($conn, $queryDelete);

    // Cek apakah query berhasil dan file gambar ada
    if (mysqli_affected_rows($conn) > 0) {
        // Hapus gambar dari folder 'img/'
        if (file_exists('img/' . $imageName)) {
            unlink('img/' . $imageName);
        }

        echo "<script>
            alert('Data berhasil dihapus');
            document.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus data');
        </script>";
    }
}


// if(isset($_POST["submit"])){
//     if($_FILES["image"]["error"] === 4){
//         echo "<script> alert('Gambar tidak ditemukan') </script>";
//     }else{
//         $fileName = $_FILES["image"]["name"];
//         $fileSize = $_FILES["image"]["size"];
//         $tmpName = $_FILES["image"]["tmp_name"];

//         $name = htmlspecialchars($_POST["name"]); 
//         $validExtension = ['jpg', 'jpeg', 'png'];
//         $imageExtension = explode('.', $fileName);
//         $imageExtension = strtolower(end($imageExtension));

//         if(!in_array($imageExtension, $validExtension)){
//             echo "<script> alert('Jenis File tidak diterima') </script>";
//         }else if($fileSize > 10000000){
//             echo "<script> alert('Ukuran gambar terlalu besar') </script>";
//         }else{
//             $newImageName = uniqid();
//             $newImageName .= '.' . $imageExtension;

//             move_uploaded_file($tmpName, 'img/'. $newImageName);
//             $queryUpload = "insert into upload values('', '$name', '$newImageName')";
//             mysqli_query($conn, $queryUpload);
//             echo "<script> alert('gambar telah berhasil di upload') </script>";
//         }
//     }
// }

if(isset($_POST["submit"])){
    $name = htmlspecialchars($_POST["name"]);

    if(isset($_FILES["image"]["name"])){
        $totalFiles = count($_FILES["image"]["name"]);

        for($i = 0; $i < $totalFiles; $i++){
            $fileName = $_FILES["image"]["name"][$i];
            $fileSize = $_FILES["image"]["size"][$i];
            $tmpName = $_FILES["image"]["tmp_name"][$i];

            $validExtension = ['jpg', 'jpeg', 'png'];
            $imageExtension = explode('.', $fileName);
            $imageExtension = strtolower(end($imageExtension));

            if(!in_array($imageExtension, $validExtension)){
                echo "<script> alert('Jenis File $fileName tidak diterima') </script>";
                continue;
            }

            if($fileSize > 10000000){
                echo "<script> alert('Ukuran gambar $fileName terlalu besar') </script>";
                continue;
            }

            $newImageName = uniqid() . '.' . $imageExtension;
            move_uploaded_file($tmpName, 'img/'. $newImageName);

            $queryUpload = "INSERT INTO upload (id, name, image) VALUES ('', '$name', '$newImageName')";
            mysqli_query($conn, $queryUpload);
        }

        echo "<script> alert('Gambar-gambar telah berhasil di-upload') </script>";
         header("Location: index.php");
        exit(); 
    } else {
        echo "<script> alert('Tidak ada gambar yang di-upload') </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>CRUD</title>
</head>
<body>
    <header>
        <p>Ini Header</p>
    </header>

    <div class="container">
        <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
            <label for="name">Nama File :</label>
            <input type="text" name="name" id="name" required value="">
            <input type="file" multiple name="image[]" id="image" 
            accept=".jpg, .jpeg, .png">
            <button type="submit" name="submit">Submit</button>
        </form>
        <div class="tampilData">
            <table>
                <tr>
                    <td>#</td>
                    <td>Nama</td>
                    <td>Gambar</td>
                    <td>Hapus</td>
                </tr>
                <?php
                    $i = 1;
                    $baris = mysqli_query($conn, $queryTampil);
                ?>

                <?php foreach($baris as $list) :?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $list["name"]; ?></td>
                    <td><img src="img/<?php echo $list["image"]; ?>" alt="<?php echo $list["image"]; ?>" width="500"></td>
                    <td>
                        <a href="index.php?delete=<?php echo $list["id"]; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

</body>
</html>