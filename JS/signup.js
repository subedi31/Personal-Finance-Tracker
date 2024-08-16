function validateSingupForm() {
    var fullname = document.getElementById("Fullname").value;
    var email = document.getElementById("Email").value;
    var password = document.getElementById("password").value;
    var confirmpassword = document.getElementById("confirm-password").value;

    if (fullname == "" || email == "" || password == "" || confirmpassword == ""){
        alert("All fields are required.");
        return false;
    }
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if(!emailPattern.test(email)) {
        alert("Please enter a valid email address.")
        return false;
    }
    var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordPattern.test(password)) {
        alert("Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");
        return false;
    }
    if (confirmpassword !== password){
        alert("Password do not match.");
        return false;
    }

    return true;
}
