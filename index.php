<?php
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<title>فرم ثبت‌نام همایش</title>
<style>
body{font-family:Tahoma,Arial,sans-serif;max-width:600px;margin:50px auto;direction:rtl}
label{display:block;margin-top:15px}
input,select{width:100%;padding:8px}
button{margin-top:20px;padding:10px 20px}
.error{color:red}
</style>
</head>
<body>
<h1>ثبت‌نام در همایش</h1>
<?php if(isset($_GET['err'])): ?>
<p class="error"><?=htmlspecialchars($_GET['err'])?></p>
<?php endif;?>
<form method="post" action="register.php">
<label>نام* <input type="text" name="first_name" required></label>
<label>نام خانوادگی* <input type="text" name="last_name" required></label>
<label>جنسیت*
<select name="gender" required>
  <option value="" disabled selected>انتخاب کنید</option>
  <option value="male">مرد</option>
  <option value="female">زن</option>
  <option value="other">دیگر</option>
</select>
</label>
<label>شرکت/سازمان* <input type="text" name="company" required></label>
<label>سمت <input type="text" name="position"></label>
<label>شماره همراه* <input type="text" name="mobile" required></label>
<label>ایمیل <input type="email" name="email"></label>
<button type="submit">درخواست شرکت در همایش</button>
</form>
</body>
</html>
