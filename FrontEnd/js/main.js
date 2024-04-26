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
      if (response.error) {
        // Displaying an error message and redirecting to index.html if there's an error
        Swal.fire({
          icon: "error",
          title: "เกิดข้อผิดพลาด", // Error title (in Thai)
          text: response.error, // Error message from the server
        }).then((result) => {
          window.location.href = "../index.html"; // Redirecting to index.html
        });
      } else {
        // Displaying a welcome message for the admin if no error
        document.getElementById("admin").innerHTML = `สวัสดีแอดมิน ${response.data.name}`; // Displaying admin name
      }
    })
    .catch((error) => {
      return error; // Returning any errors encountered during fetch
    });
};

// Calling the function to check login status
checkLoginStatus();

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

  // Sending a POST request to the logout endpoint
  await fetch("https://api.wakeupcoding.com/pharmacy-api/users/logout", requestOptions)
    .then(async (result) => {
      // Parsing response JSON
      let response = await result.json();
      // Clearing local storage
      localStorage.clear();
      // Displaying an info message using Swal (SweetAlert) and redirecting to index.html after user interaction
      Swal.fire({
        icon: "info",
        title: "กำลังออกจากระบบ", // Info title (in Thai)
        text: response.message, // Info message from server
      }).then((result) => {
        window.location.href = "../index.html"; // Redirecting to index.html
      });
    })
    .catch((error) => {
      console.log("error", error); // Logging any errors to console
    });
};
