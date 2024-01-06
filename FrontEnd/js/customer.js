customerList = async (id) => {
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");

  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);

  var requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  await fetch(`https://api.wakeupcoding.com/pharmacy-api/customer/list/${id}`, requestOptions)
    .then(async (result) => {
      let response = await result.json();
      if (response) {
        localStorage.setItem("customer_id", response.customer.id);
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
      console.log("error", error);
    });
};

callListCustomer = async () => {
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  if (token) {
    myHeaders.append("Content-Type", "application/json");
    myHeaders.append("Authorization", `Bearer ${token}`);

    var requestOptions = {
      method: "GET",
      headers: myHeaders,
      redirect: "follow",
    };

    await fetch("https://api.wakeupcoding.com/pharmacy-api/userCustomer/listCustomerId", requestOptions)
      .then(async (result) => {
        let response = await result.json();
        if (response) {
          localStorage.setItem("customer_id", response.userCustomer.customer_id);
          customerList(response.userCustomer.customer_id);
        }
      })
      .catch((error) => {
        console.log("error", error);
        Swal.fire({
          icon: "warning",
          title: "คำเตือน",
          text: "UserCustomer not found",
        }).then(() => {
          localStorage.clear();
          window.location.reload();
        });
      });
  } else {
    document.getElementById("top-account").innerHTML = "";
    document.getElementById("top-account").innerHTML = `
        <a href="#modal-register" id="noLogin" data-lightbox="inline">
            <i class="bi-person me-1 position-relative" style="top: 1px;"></i>
            <span class="d-none d-sm-inline-block font-primary fw-medium">เข้าสู่ระบบ</span>
        </a>
    `;
  }
};
callListCustomer();
