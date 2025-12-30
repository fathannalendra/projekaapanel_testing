<!DOCTYPE html>
<html>
<head>
    <title>Halaman Login</title>
</head>
<body>

    <div style="width: 300px; margin: 100px auto; border: 1px solid #ccc; padding: 20px; border-radius: 5px;">
        <h2 style="text-align: center;">Silakan Login</h2>

        <form action="<?php echo base_url('auth/proses_login'); ?>" method="post">
            
            <p>Username:</p>
            <input type="text" name="username" placeholder="Masukkan username" required style="width: 100%; padding: 5px;">
            
            <p>Password:</p>
            <input type="password" name="password" placeholder="Masukkan password" required style="width: 100%; padding: 5px;">
            
            <br><br>
            <button type="submit" style="width: 100%; padding: 10px; background: blue; color: white; border: none; cursor: pointer;">LOGIN</button>
        
        </form>
    </div>

</body>
</html>