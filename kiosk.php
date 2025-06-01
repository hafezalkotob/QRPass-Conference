<!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<title>کِیوسک حضور همایش</title>
<style>
body{font-family:tahoma;text-align:center;background:#181818;color:#fff}
#msg{font-size:2rem;margin-top:20vh}
.success{color:#0f0}
.error{color:#f33}
input{opacity:0;position:absolute}
</style>
</head>
<body>
<h1>لطفاً بلیت خود را اسکن کنید</h1>
<div id="msg">...</div>
<input id="scanner" autofocus>
<script>
const input = document.getElementById('scanner');
const msg   = document.getElementById('msg');

/* همیشه فوکوس را روی input نگه دار */
setInterval(()=>input.focus(), 500);

/* هر بار Enter زده شد */
input.addEventListener('keydown', e => {
  if (e.key !== 'Enter') return;
  const text = input.value.trim();
  input.value = '';               // خالی‌کردن برای اسکن بعدی

  /* استخراج uuid از هر چیزی که خوانده شده */
  const match = text.match(/[0-9a-f]{32}/i);
  if(!match){
    flash('کد پیدا نشد', 'error');
    return;
  }
  const code = match[0];

  /* درخواست به سرور */
  fetch(`/attend.php?code=${code}`)
    .then(r => r.json())
    .then(json => {
      if(json.ok){
        flash('خوش آمدید ✔', 'success');
      }else{
        flash('خطا یا تکراری ✖', 'error');
      }
    })
    .catch(()=>flash('عدم ارتباط با سرور', 'error'));
});

/* نمایش پیام به‌همراه افکت رنگی */
function flash(text, cls){
  msg.textContent = text;
  msg.className   = cls;
  document.body.style.background = (cls==='success')?'#003300':'#330000';
  setTimeout(() => {
    msg.textContent = 'لطفاً بلیت خود را اسکن کنید';
    msg.className = '';
    document.body.style.background = '#181818';
  }, 3000);
}
</script>
</body>
</html>




<script src="https://unpkg.com/html5-qrcode"></script>
<div id="reader" style="width:300px;margin:20px auto"></div>
<script>
const qr = new Html5Qrcode("reader");
qr.start(
  { facingMode:"environment" },
  { fps:10, qrbox:250 },
  (decodedText) => {
      /* همان منطق قبلی */
      document.getElementById('scanner').value = decodedText;
      const evt = new KeyboardEvent('keydown', {key:'Enter'});
      document.getElementById('scanner').dispatchEvent(evt);
  }
);
</script>

