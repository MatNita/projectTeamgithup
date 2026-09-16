function registerPage(event) {

    event.preventDefault();

    let name = document.querySelector(
        'input[placeholder="Enter your full name"]'
    ).value;

    let email = document.querySelector(
        'input[placeholder="you@example.com"]'
    ).value;

    let password =
        document.getElementById("password").value;

    let confirmPassword =
        document.getElementById("confirmPassword").value;


    // Check password
    if (password !== confirmPassword) {

        alert("Passwords do not match!");

        return;
    }


    // Get existing users
    let users =
        JSON.parse(localStorage.getItem("users")) || [];


    // Check duplicate email
    let existingUser = users.find(
        user => user.email === email
    );

    if (existingUser) {

        alert("This email is already registered!");

        return;
    }


    // Create new user
    let newUser = {

        id: "USR-" + Date.now(),

        name: name,

        email: email,

        password: password,

        role: "Customer",

        status: "Active"

    };


    // Add user
    users.push(newUser);


    // Save users
    localStorage.setItem(
        "users",
        JSON.stringify(users)
    );


    alert("Account created successfully!");


    // Go to Login
    window.location.href = "login.html";
}
