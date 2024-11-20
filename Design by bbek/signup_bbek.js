function data()
{
    var regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/;
    var regex1 = /^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
var nam=document.getElementById("name").value;
var address=document.getElementById("add").value;
var email=document.getElementById("email").value;
var phone=document.getElementById("phone").value;
var password=document.getElementById("pass").value;
var conf=document.getElementById("confpass").value;

if( nam=="" || address=="" || email=="" || phone=="" || password=="" || conf=="")
{
    alert("All the fields are mandatory");
    return false;
} 

else if(phone.length>10 || phone.length<10)
    {
        alert("Number should be of 10 digit. Please enter valid number");
        return false;
    }

else if(isNaN(phone))
        {
            alert("Only number is allowed");
            return false;
        }

else if (!regex1.test(email))
            {
                alert("Invalid email");
                return false;
            }

else if (!regex.test(password)) 
{
    alert("Password must contain at least one uppercase letter, one number, and one special character.");
    return false;
}

 else if (password != conf)
  {
    alert("Password must be same.");
    return false;
}

else if(!isNaN(nam))
    {
        alert("Number is not allowed in Full Name");
        return false;
    }

else if(!isNaN(address))
        {
            alert("Number is not allowed in Address");
            return false;
        }

    else {
        return true;
    }



}