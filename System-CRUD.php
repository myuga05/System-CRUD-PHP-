<?php
//  Konfigurasi koneksi ke database
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "siakad";

// Create Koneksi ke database
$koneksi    = mysqli_connect($host, $user, $pass, $db);

// Check koneksi
if(!$koneksi) 
{
    die("Tidak bisa terkoneksi ke database");
} 

$nrp        = "";
$nama       = "";
$alamat     = "";
$fakultas   = "";
$sukses     = "";
$error      = "";

// Validasi operator ada di URL
if(isset($_GET['op'])) 
{
    $op = $_GET['op'];
} else {
    $op = "";
}

// Proses Hapus Data
if($op == 'delete')
{
    $id    = $_GET['id'];
    $sql1   = "DELETE FROM mahasiswa WHERE id = '$id'";
    $q1     = mysqli_query($koneksi, $sql1);
    if($q1)
    {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}

// Proses Edit Data
if($op == 'edit') {
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    if ($id) {
        $sql1 = "SELECT * FROM mahasiswa WHERE id = '$id'";
        $q1 = mysqli_query($koneksi, $sql1);
        if (mysqli_num_rows($q1) > 0) {
            $r1 = mysqli_fetch_array($q1);
            $nrp = $r1['NRP'];
            $nama = $r1['Nama'];
            $alamat = $r1['Alamat'];
            $fakultas = $r1['Fakultas'];
        } else {
            $error = "Data tidak ditemukan";            
        }
    } else {
        $error = "ID tidak valid";        
    }
}

// Proses penyimpanan data
if(isset($_POST['simpan']))
{ 
    $nrp        = $_POST['nrp'];
    $nama       = $_POST['nama'];
    $alamat     = $_POST['alamat'];
    $fakultas   = $_POST['fakultas'];  
    
    // Validasi data telah terisi
    if($nrp && $nama && $alamat && $fakultas){
        if($op == 'edit')
        { 
            // Proses update data
            $sql1   = "UPDATE mahasiswa SET nrp = '$nrp', nama = '$nama', alamat = '$alamat', fakultas = '$fakultas' WHERE id = '$id'";
            $q1     = mysqli_query($koneksi, $sql1);
            if($q1)
            {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }        
        } else {
            // Proses insert data 
            $sql_check  = "SELECT * FROM mahasiswa WHERE nrp = '$nrp'";
            $q_check    = mysqli_query($koneksi, $sql_check);
            if(mysqli_num_rows($q_check) > 0)
            {
                $error  = "NRP sudah ada, silahkan masukkan NRP yang baru";
            } else {
                $sql1   = "insert into mahasiswa(nrp, nama, alamat, fakultas) values ('$nrp','$nama','$alamat','$fakultas')";
                $q1     = mysqli_query($koneksi, $sql1);
                if($q1) 
                {
                    $sukses = "Berhasil memasukkan data baru";
                } else {
                    $error  = "Gagal memasukkan data";
                }
            }
        }
        
    } else {
        $error = "Silahkan masukkan semua data";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .mx-auto { width:800px; }
        .card { margin-top:10px; }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- Untuk Memasukkan Data -->
        <div class="card">
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php
                if($error)
                {
                    ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error ?>
                        </div>
                    <?php
                        header("refresh:1;url=index.php"); 
                }
                ?>

                <?php
                if($sukses)
                {
                    ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $sukses ?>
                        </div>
                    <?php 
                        header("refresh:1;url=index.php");  
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nrp" class="col-sm-2 col-form-label">NRP</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nrp" name="nrp" value="<?php echo $nrp ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="fakultas" id="fakultas">
                                <option value="">- Pilih Fakultas -</option>
                                <option value="FSAD"<?php if($fakultas == "FSAD") echo "selected"?>>FSAD</option>
                                <option value="FTIRS"<?php if($fakultas == "FTIRS") echo "selected"?>>FTIRS</option>
                                <option value="FTSPK"<?php if($fakultas == "FTSPK") echo "selected"?>>FTSPK</option>
                                <option value="FV"<?php if($fakultas == "FV") echo "selected"?>>FV</option>
                                <option value="FKK"<?php if($fakultas == "FKK") echo "selected"?>>FKK</option>
                                <option value="FTK"<?php if($fakultas == "FTK") echo "selected"?>>FTK</option>
                                <option value="FTEIC"<?php if($fakultas == "FTEIC") echo "selected"?>>FTEIC</option>
                                <option value="FDKBD"<?php if($fakultas == "FDKBD") echo "selected"?>>FDKBD</option>
                            </select>    
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary"/>
                    </div>
                </form>
            </div>
        </div>

        <!-- Untuk Mengeluarkan Data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Mahasiswa
            </div>
            <div class="card-body">
               <table class="table">
                    <thead>
                        <tr>                            
                            <th scope="col">#</th>
                            <th scope="col">NRP</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Fakultas</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php                        
                        $sql2 = "SELECT * FROM mahasiswa ORDER BY id DESC";
                        $q2   = mysqli_query($koneksi, $sql2);                        

                        $urut = 1;
                        while($r2 = mysqli_fetch_array($q2))
                        {
                            $id         = $r2['ID']; 
                            $nrp        = $r2['NRP'];
                            $nama       = $r2['Nama'];
                            $alamat     = $r2['Alamat'];
                            $fakultas   = $r2['Fakultas'];
                           
                            ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $nrp ?></td>
                                <td scope="row"><?php echo $nama ?></td>
                                <td scope="row"><?php echo $alamat ?></td>
                                <td scope="row"><?php echo $fakultas ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id?>" onclick = "return confirm('Are you sure want to delete this data?')"><button type="button" class="btn btn-danger">Delete</button></a>                                                                        
                                </td>
                            </tr>
                            <?php                            
                        }
                        ?>
                    </tbody>                    
               </table>
            </div>
        </div>

    </div>
</body>
</html>