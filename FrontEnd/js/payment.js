(function () {
  "use strict";

  var forms = document.querySelectorAll(".needs-validation");

  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        } else {
          customerCreate();
          event.preventDefault();
        }

        form.classList.add("was-validated");
      },
      false
    );
  });
})();

orDerDetailList = async (orderId) => {
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);

  var requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  await fetch(`https://api.wakeupcoding.com/pharmacy-api/orderDrugDetail/list/${orderId}`, requestOptions)
    .then(async (result) => {
      let response = await result.json();
      let output = "";
      let total = 0;
      let no = 1;
      console.log("response", response.orderDrugDetail);
      response.orderDrugDetail.forEach((element) => {
        output += `
          <li class="list-group-item d-flex justify-content-between lh-sm">
              <div>
                  <h6 class="my-0">${no}. ${element.drugInfoName}</h6>
              </div>
              <span class="text-muted">${element.value}฿</span>
          </li>
        `;
        total += element.total;
        no++;
      });
      $(".order-code").html(orderId);
      $(".show-cart").html(output);
      $(".total-cart").html(`${total.toFixed(2)}฿`);
      $(".total-count").html(response.orderDrugDetail.length);
    })
    .catch((error) => {
      console.log("error", error);
    });
};

getOrderId = () => {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const param = urlParams.get("orderId");
  console.log("param:", param);

  if (param) {
    orDerDetailList(param);
  }
};
getOrderId();

customerCreate = async () => {
  document.getElementById("load").style.display = "flex";
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);

  var raw = JSON.stringify({
    fName: $("#firstName").val(),
    lName: $("#lastName").val(),
    address: $("#address").val(),
    phoneNumber: $("#phonenumber").val(),
    email: $("#email").val(),
    discount: 0,
  });

  var requestOptions = {
    method: "POST",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  await fetch("https://api.wakeupcoding.com/pharmacy-api/customer/create", requestOptions)
    .then(async (result) => {
      let response = await result.json();
      console.log("response", response);
      if (response.message) {
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
      console.log("error", error);
      Swal.fire({
        icon: "error",
        title: "ผิดพลาด",
        text: error.error,
      });
    });
};
