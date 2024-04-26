customerList = async (id) => {
  // Setting up request headers
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);

  // Setting up request options
  var requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  // Sending a GET request to retrieve customer information
  await fetch(`https://api.wakeupcoding.com/pharmacy-api/customer/list/${id}`, requestOptions)
    .then(async (result) => {
      // Parsing response JSON
      let response = await result.json();
      if (response) {
        // Storing customer ID in local storage
        localStorage.setItem("customer_id", response.customer.id);
        // Displaying customer information in the navbar
        document.getElementById("top-account").innerHTML = "";
        document.getElementById("top-account").innerHTML = `
          <div class="dropdown">
              <a href="#" class="dropdown-toggle" role="button" id="hasLogin"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  คุณ ${response.customer.fName} ${response.customer.lName}
              </a>
              <ul class="dropdown-menu" aria-labelledby="hasLogin">
                  <li><a class="dropdown-item" href="history.html">ประวัติคำสั่งซื้อ</a></li>
                  <li><a class="dropdown-item" href="address.html">จัดการที่อยู่</a></li>
                  <li><a class="dropdown-item" href="#" onclick="logout()">ออกจากระบบ</a></li>
              </ul>
          </div>
        `;
      }
    })
    .catch((error) => {
      console.log("error", error); // Logging any errors to console
    });
};

callListCustomer = async () => {
  // Setting up request headers
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  // Checking if token exists
  if (token) {
    myHeaders.append("Content-Type", "application/json");
    myHeaders.append("Authorization", `Bearer ${token}`);

    // Setting up request options
    var requestOptions = {
      method: "GET",
      headers: myHeaders,
      redirect: "follow",
    };

    // Sending a GET request to retrieve customer ID
    await fetch("https://api.wakeupcoding.com/pharmacy-api/userCustomer/listCustomerId", requestOptions)
      .then(async (result) => {
        // Parsing response JSON
        let response = await result.json();
        if (response) {
          // Storing customer ID in local storage
          localStorage.setItem("customer_id", response.userCustomer.customer_id);
          // Calling customerList function to display customer information
          customerList(response.userCustomer.customer_id);
        }
      })
      .catch((error) => {
        console.log("error", error); // Logging any errors to console
        // Displaying a warning message if UserCustomer is not found and clearing local storage
        Swal.fire({
          icon: "warning",
          title: "คำเตือน", // Alert title (in Thai)
          text: "UserCustomer not found", // Warning message
        }).then(() => {
          localStorage.clear();
          window.location.reload(); // Reloading the page
        });
      });
  } else {
    // Displaying login link if token doesn't exist
    document.getElementById("top-account").innerHTML = "";
    document.getElementById("top-account").innerHTML = `
        <a href="#modal-login" id="noLogin" data-lightbox="inline">
            <i class="bi-person me-1 position-relative" style="top: 1px;"></i>
            <span class="d-none d-sm-inline-block font-primary fw-medium">เข้าสู่ระบบ</span>
        </a>
    `;
  }
};

// Calling the function to fetch and display customer information
callListCustomer();
