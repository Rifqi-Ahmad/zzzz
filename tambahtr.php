<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<style>
footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  color: gray;
  text-align: center;
}
</style>
<?php

    include './koneksi.php';

    session_start();
    $diskon=0;
    $diskon1=0;
    if(isset($_POST["tmb"])){
        $_SESSION["datatr"]=$_POST["kodetr"];
        $_SESSION["datatgl"]=$_POST["tgl"];
        $_SESSION["datacust"]=$_POST["cust"];
        
        //aray ambil data kode
        $x=$_POST["kode"];
        $_SESSION["data"][$x]["kode"]=$_POST["kode"];
        $_SESSION["data"][$x]["nama"]=$_POST["namabr"];
        $_SESSION["data"][$x]["satuan"]=$_POST["satuanbr"];
        $_SESSION["data"][$x]["jumlah"]=$_POST["jml"];
        $_SESSION["data"][$x]["diskon"]=$_POST["diskonbr"];
        $_SESSION["data"][$x]["harga"]=$_POST["hargabr"];


        $_SESSION["datatr1"]=$_POST["kodetr"];
        $_SESSION["datatgl1"]=$_POST["tgl"];
        $_SESSION["datacust1"]=$_POST["cust"];
        
        //aray nota
        $x1=$_POST["kode"];
        $_SESSION["data1"][$x1]["kode"]=$_POST["kode"];
        $_SESSION["data1"][$x1]["nama"]=$_POST["namabr"];
        $_SESSION["data1"][$x1]["satuan"]=$_POST["satuanbr"];
        $_SESSION["data1"][$x1]["jumlah"]=$_POST["jml"];
        $_SESSION["data1"][$x1]["diskon"]=$_POST["diskonbr"];
        $_SESSION["data1"][$x1]["harga"]=$_POST["hargabr"];

        //ambil diskon
        $query = "SELECT diskon from barang WHERE kodebr = '$x'";
        $sql = mysqli_query($koneksi, $query);
        $row = mysqli_fetch_assoc($sql);
        $diskon = $row['diskon'];
        $_SESSION['dis'][$x]['diskon'] = $diskon;

        foreach($_SESSION['dis'] as $x => $y){
            $diskon1 = $diskon1 + $y['diskon'];
        }
        $_SESSION['diskon'] = $diskon1;
        $_SESSION['bayar']=0;

        $_POST["kirim"]='0';
     }
     if(isset($_POST["delete"])){
         $xx=$_POST["kod"];
         unset($_SESSION["data"][$xx]);
         $_POST["delete"]='0';
     }
     if(isset($_POST["clear"])){
         unset($_SESSION["data"]);
         unset($_SESSION["datatr"]);
         unset($_SESSION["datatgl"]);
         unset($_SESSION['datacust']);
         unset($_SESSION["diskon"]);
         unset($_SESSION["total"]);
         unset($_SESSION['bayar']);
         $_POST["clear"]='0';
     }
     if(isset($_POST["bayar"])){
         $_SESSION["bayar"]=$_POST["bayar"];
         $_SESSION["bayar1"]=$_POST["bayar"];
         $_POST["diskonkirim"]="0";
     }
     if(isset($_POST["simpan"])&&isset($_SESSION["data"])){
         $_POST["kirimsave"]="0";
         $datatr=$_SESSION["datatr"];
         $datatgl=$_SESSION["datatgl"];
         $datacust=$_SESSION["datacust"];
         $totalakhir=$_SESSION["total"];
         $diskonakhir=$_SESSION["diskon"];
 
         $sqlm = "INSERT INTO transaksi(kodetr, tanggal, customer, total) VALUES('$datatr','$datatgl','$datacust',$totalakhir)" ;
         mysqli_query($koneksi, $sqlm);
 
         foreach($_SESSION["data"] as $yy=>$yy_value){
             $kode=$_SESSION["data"][$yy]["kode"];
             $nama=$_SESSION["data"][$yy]["nama"];
             $satuan=$_SESSION["data"][$yy]["satuan"];
             $jumlah=$_SESSION["data"][$yy]["jumlah"];
             $harga=$_SESSION["data"][$yy]["harga"];
 
             $sqld = "INSERT INTO tbldetail(kodetr, kode, nama, satuan, jumlah, harga) VALUES('$datatr','$kode','$nama','$satuan',$jumlah,$harga)";
             mysqli_query($koneksi,$sqld);
         }

         $mypdf = "nota.php";
         echo "<script>window.open('$mypdf', '_blank');</script>";

        unset($_SESSION["data"]);
        unset($_SESSION["datatr"]);
        unset($_SESSION["datatgl"]);
        unset($_SESSION["datacust"]);
        unset($_SESSION["bayar"]);
        unset($_SESSION["kembalian"]);
        unset($_SESSION["total"]);


     }
 
     $sqlc = "SELECT count(*)as jum FROM transaksi";
     $result1= mysqli_query($koneksi, $sqlc);
     $row = mysqli_fetch_assoc($result1);
     $jumlahrecord= "TR".($row["jum"]+1);
     $_SESSION["datatr"]=$jumlahrecord;

 

?>




<title>-- INI UAS --</title>
</head>
<body>
    <nav class="nav justify-content-lg-center">
        <span class="navbar-text">
            <h1>Tambah Transaksi</h1>
            </span>
    </nav>
    <br>
<div class="container">
    <div class="row">
        <div class="col-3">
            <ul class="nav text-justify flex-column">
                <li>
                    <a type="button" class="btn btn-primary" href="index.php" >Home</a>
                </li>
                <br>
                <li class="nav-item">
                    <a type="button" class="btn btn-primary" href="master_brg.php" >Master Barang</a>
                </li>
            <br>
                <li class="nav-item">
                    <a type="button" class="btn btn-outline-primary" href="transaksi.php">Transaksi</a>
                </li>
            </ul>
        </div>

        <div class="col-md">
            <form action="" method="post">
                <input type="submit" class="btn btn-success" name="simpan" value="Simpan">
                <input type="submit" class="btn btn-danger" name="clear" value="Clear">
                <a href="transaksi.php" class="btn btn-dark" >Cancel</a>
                <br>
                <br>
                <div class="form-group row">
                    <div class="col-xs-2">
                        <input type="text" class="form-control" name="kodetr" value="<?=$jumlahrecord?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-2">
                        <input type="date" class="form-control" name="tgl" value="<?php if(isset($_SESSION['datatgl'])){echo $_SESSION['datatgl'];} ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-2">
                        <input type="text" class="form-control" name="cust" value="<?php if(isset($_SESSION['datacust'])){echo $_SESSION['datacust'];} ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                    <select class="btn btn-secondary" name="kode" onchange="changeValue(this.value)">
                        <option value="">-Pilih-</option>
                                <?php
                                    $jsArray = "var barang = new Array();\n"; 
                                    $query = "SELECT * FROM barang ORDER BY kodebr ASC";
                                    $sql = mysqli_query($koneksi, $query);
                                    while($row = mysqli_fetch_assoc($sql)){

                                        echo '<option value="' . $row['kodebr'] . '">' . $row['kodebr'] . '</option>';  
                                        $jsArray .= "barang['" . $row['kodebr'] . "'] = {nama:'" . addslashes($row['nama']) . "',satuan:'".addslashes($row['satuan']) . "',harga:'".addslashes($row['hargajual']) . "',diskon:'".addslashes($row['diskon'])."'};\n";  

                                    }
                                        
                                ?>
                               
                    </select>
                            
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="namabr" id="namabr" value="">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="satuanbr" id="satuanbr" value="">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" name="hargabr" id="hargabr" value="">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" name="diskonbr" id="diskonbr" value="">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" name="jml" id="jml">
                    </div>
                    <div class="col">
                        <input type="submit" class="btn btn-info" name="tmb" value="+">
                    </div>
                </div>
            </form>
            
            <!-- auto fill dari kode brg -->
            <script type="text/javascript"> 
                <?php echo $jsArray; ?>
                function changeValue(id){
                document.getElementById('namabr').value = barang[id].nama;
                document.getElementById('satuanbr').value = barang[id].satuan;
                document.getElementById('hargabr').value = barang[id].harga;
                document.getElementById('diskonbr').value = barang[id].diskon;
                };
            </script>

            
            <?php
                if(isset($_SESSION["data"])){
                    echo "<Table border=\"0\" class=\"table\">";
                    echo "<tr><th>Kode</th><th>Nama</th><th>Satuan</th><th>Jumlah</th><th>Harga</th><th>Total</th></tr>";
                    $total=0;
                    foreach($_SESSION["data"] as $y => $y_value)
                    {
                        echo "<tr><form action=\"\" method=\"POST\"><input type=\"text\" name=\"kod\" value=\"$y\" hidden>";
                        echo "<td>";
                        echo $_SESSION["data"][$y]["kode"];
                        echo "</td>";
                        echo "<td>";
                        echo $_SESSION["data"][$y]["nama"];
                        echo "</td>";
                        echo "<td>";
                        echo $_SESSION["data"][$y]["satuan"];
                        echo "</td>";
                        echo "<td>";
                        echo $_SESSION["data"][$y]["jumlah"];
                        echo "</td>";
                        echo "<td>";
                        echo $_SESSION["data"][$y]["harga"];
                        echo "</td>";
                        echo "<td>";
                        echo (1-($_SESSION['data'][$y]['diskon'])/100) * $_SESSION["data"][$y]["jumlah"] * $_SESSION["data"][$y]["harga"];
                        $total= $total + (1-($_SESSION['data'][$y]['diskon'])/100) * $_SESSION["data"][$y]["jumlah"] * $_SESSION["data"][$y]["harga"];
                        echo "</td>";
                        echo "<td><input type=\"submit\" name=\"delete\" value=\"X\" ></td>";
                        echo "</form>";
                        
                        

                    }
                    $_SESSION["total"]=$total;
                    $_SESSION["total1"]=$total;
                    setlocale(LC_ALL, 'en_IN');
                    echo "<tr><td></td><td></td><td></td><td></td><td>";
                    echo "Total :</td><td>Rp ";
                    echo number_format($_SESSION["total"],2,",",".");
                    echo "</td></tr>";

                    echo "<tr><form action=\"\" method=\"POST\">";
                    echo "<td></td><td></td><td></td><td></td><td>Bayar : </td><td><input type=\"text\" name=\"bayar\" value=\"".$_SESSION['bayar']."\"";
                    echo " \"></td>";
                    echo "</form></tr>";
                    echo "<tr><td></td><td></td><td></td><td></td><td>";
                    echo "Kembalian :</td><td>Rp ";
                    if($_SESSION['bayar'] == 0){
                        $kembalian = 0;
                    }else{
                        $kembalian=$_SESSION['bayar'] - $_SESSION["total"];
                        $_SESSION['kembalian'] = $kembalian;
                        $_SESSION['kembalian1'] = $kembalian;
                    }
                    echo number_format($kembalian,2,",",".");
                    echo "</td></tr>";
                    echo "</table>";
                }
            ?>
        </div>
        
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
<footer class="text-center">
        Copyright &copy; Rifqi Ahmad | Niko Christian
</footer>
</html>