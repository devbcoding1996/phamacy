let role;
handleLogin = async () => {
  // Retrieving username and password from the login form
  var _user = document.getElementById("login-form-username").value;
  var _pass = document.getElementById("login-form-password").value;

  // Displaying loading spinner
  document.getElementById("load").style.display = "flex";
  try {
    // Setting up request headers
    var myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");
    myHeaders.append("Cookie", "isAdmin=true");

    // Creating JSON string from username and password
    var raw = JSON.stringify({
      email: _user,
      password: _pass,
    });

    // Setting up request options
    var requestOptions = {
      method: "POST",
      headers: myHeaders,
      body: raw,
      redirect: "follow",
    };

    // Sending login request to server
    const response = await fetch("https://api.wakeupcoding.com/pharmacy-api/users/login", requestOptions)
      .then((result) => {
        return result.json(); // Parsing response JSON
      })
      .catch((error) => {
        return error; // Catching any errors during fetch
      });

    // Handling login response
    if (response.message === "successfully") {
      // Storing token in local storage
      localStorage.setItem("token", response.token);
      // Checking login status
      await checkLoginStatus();
    } else {
      // Hiding loading spinner
      document.getElementById("load").style.display = "none";
      // Displaying error message
      Swal.fire({
        icon: "error",
        title: "คำเตือน",
        text: response.error || "Login failed",
      });
    }
  } catch (error) {
    console.error("Error:", error); // Logging any errors to console
  }
};

checkLoginStatus = async () => {
  // Setting up request headers
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);
  myHeaders.append("Cookie", "isAdmin=true");

  // Setting up request options
  var requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  // Sending a GET request to check login status
  await fetch("https://api.wakeupcoding.com/pharmacy-api/users", requestOptions)
    .then(async (result) => {
      // Parsing response JSON
      let response = await result.json();
      // Checking if response data exists
      if (response.data) {
        // Checking if the user is an admin
        if (response.data.isAdmin === "1") {
          // Redirecting to admin page after a delay
          setTimeout(() => {
            window.location.href = "admin/add.html";
          }, 1000);
        } else {
          // Reloading the current page if the user is not an admin
          window.location.reload();
        }
      } else {
        // Logging error if response data is not available
        console.log(response.error);
      }
    })
    .catch((error) => {
      // Catching any errors during fetch
      return error;
    });
};

logout = async () => {
  // Setting up request headers
  var myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");

  // Empty body for the request
  var raw = "";

  // Setting up request options
  var requestOptions = {
    method: "POST",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  // Sending a POST request to logout endpoint
  await fetch("https://api.wakeupcoding.com/pharmacy-api/users/logout", requestOptions)
    .then(async (result) => {
      // Parsing response JSON
      let response = await result.json();
      // Clearing token from local storage
      localStorage.clear();
      // Displaying info message using Swal (SweetAlert) and redirecting to index.html after user interaction
      Swal.fire({
        icon: "info",
        title: "กำลังออกจากระบบ", // Alert title (in Thai)
        text: response.message, // Info message from server
      }).then((result) => {
        window.location.href = "index.html"; // Redirecting to index.html
      });
    })
    .catch((error) => {
      console.log("error", error); // Logging any errors to console
    });
};
