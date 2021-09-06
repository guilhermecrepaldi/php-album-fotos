<?php
$upload_dir="uploads/";$thumb_dir="thumbs/";
if(!is_dir($upload_dir))mkdir($upload_dir,0755);
if(!is_dir($thumb_dir))mkdir($thumb_dir,0755);
if($_SERVER["REQUEST_METHOD"]==="POST"&&isset($_FILES["foto"])){
$allowed=["jpg","jpeg","png","gif"];$ext=strtolower(pathinfo($_FILES["foto"]["name"],PATHINFO_EXTENSION));
if(in_array($ext,$allowed)){$name=uniqid().".$ext";
move_uploaded_file($_FILES["foto"]["tmp_name"],$upload_dir.$name);
if($ext!=="gif"){
list($w,$h)=getimagesize($upload_dir.$name);$thumb_w=200;$thumb_h=200;
$src=imagecreatefromstring(file_get_contents($upload_dir.$name));
$thumb=imagecreatetruecolor($thumb_w,$thumb_h);
imagecopyresampled($thumb,$src,0,0,0,0,$thumb_w,$thumb_h,$w,$h);
imagejpeg($thumb,$thumb_dir.$name,80);imagedestroy($thumb);imagedestroy($src);
}else{copy($upload_dir.$name,$thumb_dir.$name);}
}header("Location: index.php");exit;}
if(isset($_GET["deletar"])){$f=$_GET["deletar"];
if(file_exists($upload_dir.$f))unlink($upload_dir.$f);
if(file_exists($thumb_dir.$f))unlink($thumb_dir.$f);
header("Location: index.php");exit;}
$fotos=array_diff(scandir($upload_dir),array(".",".."));rsort($fotos);
?><!DOCTYPE html><html lang="pt-BR">
<head><meta charset="UTF-8"><title>Album de Fotos</title>
<style>*{box-sizing:border-box}body{font-family:Arial;max-width:900px;margin:20px auto;padding:20px}
h1{margin-bottom:20px}.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:15px}
.card{background:white;border-radius:8px;box-shadow:0 2px 5px rgba(0,0,0,0.1);overflow:hidden;position:relative}
.card img{width:200px;height:200px;object-fit:cover;display:block}
.card .del{position:absolute;top:5px;right:5px;background:rgba(255,0,0,0.7);color:white;border:none;border-radius:50%;width:25px;height:25px;cursor:pointer;text-align:center;line-height:25px;text-decoration:none;font-weight:bold}
form{border:2px dashed #ddd;padding:30px;text-align:center;border-radius:8px;margin-bottom:20px}
form input{margin:10px}form button{background:#4CAF50;color:white;border:none;padding:10px 30px;border-radius:4px;cursor:pointer}
.empty{text-align:center;padding:50px;color:#999}</style></head>
<body><h1>Album de Fotos</h1>
<form method="POST" enctype="multipart/form-data">
<p>Selecione uma foto (JPG, PNG, GIF)</p>
<input type="file" name="foto" accept="image/*" required>
<button type="submit">Upload</button></form>
<?php if(count($fotos)>0):?><div class="grid">
<?php foreach($fotos as $f):?><div class="card">
<a href="<?=$upload_dir.$f?>" target="_blank"><img src="<?=$thumb_dir.$f?>" alt="foto"></a>
<a href="?deletar=<?=urlencode($f)?>" class="del" onclick="return confirm('Deletar?')">X</a></div>
<?php endforeach;?></div>
<?php else:?><div class="empty">Nenhuma foto ainda. Faca upload!</div><?php endif;?></body></html>
