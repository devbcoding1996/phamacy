(function () {
  "use strict";

  // Select all forms with the class "needs-validation"
  var forms = document.querySelectorAll(".needs-validation");

  // Convert the NodeList to an array and iterate over each form
  Array.prototype.slice.call(forms).forEach(function (form) {
    // Add an event listener for the "submit" event on each form
    form.addEventListener(
      "submit",
      function (event) {
        // Check if the form is not valid
        if (!form.checkValidity()) {
          // Prevent the form from submitting
          event.preventDefault();
          // Prevent the event from propagating to parent elements
          event.stopPropagation();
        } else {
          // If the form is valid, call the customerCreate function (assuming it's defined elsewhere)
          customerCreate();
          // Prevent the default form submission behavior
          event.preventDefault();
        }

        // Add the "was-validated" class to the form to trigger Bootstrap's validation styles
        form.classList.add("was-validated");
      },
      false
    );
  });
})();

var _total = 0;
orDerDetailList = async (orderId) => {
  // Set up request headers with authorization token
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);

  // Set up request options
  var requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  // Fetch order details from the API endpoint using orderId
  await fetch(`https://api.wakeupcoding.com/pharmacy-api/orderDrugDetail/list/${orderId}`, requestOptions)
    .then(async (result) => {
      // Parse the JSON response
      let response = await result.json();

      // Initialize variables for generating HTML output and calculating total
      let output = "";
      let total = 0;
      let quantity = 0;
      let no = 1;

      // Iterate through each order detail
      response.orderDrugDetail.forEach((element) => {
        // Generate HTML markup for each order detail
        output += `
          <li class="list-group-item d-flex justify-content-between lh-sm">
              <div>
                  <h6 class="my-0 fw-normal">${no}. ${element.drugInfoName} <span class="text-danger fw-light">(${element.quantity})ชิ้น</span></h6>
              </div>
              <span class="text-muted">${element.total}฿</span>
          </li>
        `;

        // Calculate total price
        total += element.total;
        _total += element.total;
        quantity += element.quantity;
        no++;
      });

      // Update HTML elements with order details and total
      $(".order-code").html(orderId);
      $(".show-cart").html(output);
      $(".total-cart").html(`${total.toFixed(2)}฿`);
      $(".total-count").html(quantity);
    })
    .catch((error) => {
      // Handle errors
      console.log("error", error);
    });
};

getOrderId = () => {
  // Retrieve the query string from the window's location
  const queryString = window.location.search;
  
  // Parse the query string to get URL parameters
  const urlParams = new URLSearchParams(queryString);
  
  // Get the value of the "orderId" parameter from the URL
  const param = urlParams.get("orderId");
  console.log("param:", param);

  // Check if the "orderId" parameter is present in the URL
  if (param) {
    // If the parameter is present, call the orDerDetailList function with the orderId
    orDerDetailList(param);
  }
};

// Call the getOrderId function to retrieve and process the orderId parameter from the URL
getOrderId();

customerCreate = async () => {
  // Display loading indicator
  document.getElementById("load").style.display = "flex";

  // Set up request headers with authorization token
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);

  // Create a JSON object with customer data from form inputs
  var raw = JSON.stringify({
    fName: $("#firstName").val(),
    lName: $("#lastName").val(),
    address: $("#address").val(),
    phoneNumber: $("#phonenumber").val(),
    email: $("#email").val(),
    discount: 0, // Assuming default discount is 0
  });

  // Set up request options
  var requestOptions = {
    method: "POST",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  // Send POST request to create a new customer
  await fetch("https://api.wakeupcoding.com/pharmacy-api/customer/create", requestOptions)
    .then(async (result) => {
      // Parse the JSON response
      let response = await result.json();
      if (response) {
        // If successful, update the order with the newly created customer ID
        orderDrugUpdate(response.customerId);
      }
    })
    .catch((error) => {
      // Handle errors
      console.log("error", error);
      Swal.fire({
        icon: "error",
        title: "ผิดพลาด",
        text: error.error,
      });
    });
};

orderDrugUpdate = async (customerId) => {
  // Set up request headers with authorization token
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  let orderId = localStorage.getItem("orderId");
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);

  // Create a JSON object with order update data
  var raw = JSON.stringify({
    id: orderId,
    customerId: customerId,
    total: _total, // Assuming _total is a global variable containing the total price
    status: "WP", // Assuming "WP" represents the status of the order
  });

  // Set up request options
  var requestOptions = {
    method: "PUT",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  // Send PUT request to update the order
  await fetch("https://api.wakeupcoding.com/pharmacy-api/orderDrug/update/", requestOptions)
    .then(async (result) => {
      // Parse the JSON response
      let response = await result.json();
      if (response.message) {
        // If successful, display success message and redirect to index page
        document.getElementById("load").style.display = "none";
        Swal.fire({
          icon: "success",
          title: "สำเร็จ",
          text: "คำสั่งซื้อของท่านถูกบันทึกแล้ว",
        }).then(() => {
          window.location.href = "index.html";
        });
      }
    })
    .catch((error) => {
      // Handle errors
      console.log("error", error);
      Swal.fire({
        icon: "error",
        title: "ผิดพลาด",
        text: error.error,
      });
    });
};

handleAddress = async (e) => {
  // If "e" is "new", clear the form fields
  if (e === "new") {
    $("#firstName").val("");
    $("#lastName").val("");
    $("#address").val("");
    $("#phonenumber").val("");
    $("#email").val("");
  } else {
    // If "e" is not "new", fetch the address details for the selected customer ID
    var myHeaders = new Headers();
    let token = localStorage.getItem("token");
    let cusId = localStorage.getItem("customer_id");
    myHeaders.append("Content-Type", "application/json");
    myHeaders.append("Authorization", `Bearer ${token}`);

    var requestOptions = {
      method: "GET",
      headers: myHeaders,
      redirect: "follow",
    };

    // Fetch address details for the selected customer ID
    await fetch("https://api.wakeupcoding.com/pharmacy-api/customer/list/" + cusId, requestOptions)
      .then(async (result) => {
        let response = await result.json();
        let data = response.customer;
        if (response) {
          // Populate the form fields with the fetched address details
          $("#firstName").val(data.fName);
          $("#lastName").val(data.lName);
          $("#address").val(data.address);
          $("#phonenumber").val(data.phoneNumber);
          $("#email").val(data.email);
        }
      })
      .catch((error) => {
        // Handle errors
        Swal.fire({
          icon: "error",
          title: "ผิดพลาด",
          text: error.error,
        });
      });
  }
};

// Call handleAddress with "new" to clear form fields initially
handleAddress("new");
