<?php
require_once 'header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-center mt-2 mb-5">
                    <img src="assets/ditasia-logotype.svg" alt="DIT Logo" class="img-fluid mb-4" style="max-height: 160px;">
                    <h1 class="h4">دومین نشست تخصصی «نوآوری در زیبایی DIT»</h1>
                    <p class="small">پیوند بین پژوهش‌های ضدپیری، راهکارهای مراقبتی در برابر آفتاب و تحولات بازار مصرف</p>
                </div>
                
                <?php if(isset($_GET['err'])): ?>
                <div class="alert alert-danger"><?=htmlspecialchars($_GET['err'])?></div>
                <?php endif;?>

                <form method="post" action="register.php" class="needs-validation" novalidate>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="نام" required 
                               pattern="^[\u0600-\u06FF\s]{2,}$" minlength="2">
                        <label class="small" for="first_name">نام <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">
                            لطفاً نام خود را به فارسی وارد کنید (حداقل ۲ حرف)
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="نام خانوادگی" required
                               pattern="^[\u0600-\u06FF\s]{2,}$" minlength="2">
                        <label class="small" for="last_name">نام خانوادگی <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">
                            لطفاً نام خانوادگی خود را به فارسی وارد کنید (حداقل ۲ حرف)
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="" disabled selected></option>
                            <option value="male">مرد</option>
                            <option value="female">زن</option>
                        </select>
                        <label class="small" for="gender">جنسیت <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">
                            لطفاً جنسیت خود را انتخاب کنید
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="company" name="company" placeholder="شرکت/سازمان" required
                               minlength="2">
                        <label class="small" for="company">شرکت/سازمان <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">
                            لطفاً نام شرکت یا سازمان خود را وارد کنید (حداقل ۲ حرف)
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="position" name="position" placeholder="سمت">
                        <label class="small" for="position">سمت</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="شماره همراه" required
                               pattern="^09[0-9]{9}$">
                        <label class="small" for="mobile">شماره همراه <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">
                            لطفاً شماره همراه معتبر وارد کنید (مثال: ۰۹۱۲۳۴۵۶۷۸۹)
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="ایمیل"
                               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                        <label class="small" for="email">ایمیل</label>
                        <div class="invalid-feedback">
                            لطفاً یک آدرس ایمیل معتبر وارد کنید
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-4">ثبت‌نام</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// اعتبارسنجی فرم بوت‌استرپ
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

<?php
require_once 'footer.php';
?>
