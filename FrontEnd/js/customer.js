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
        console.log("response", response);
        if (response) {
          localStorage.setItem("customer_id", response.userCustomer.customer_id);
          document.getElementById("top-account").innerHTML = "";
          document.getElementById("top-account").innerHTML = `
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" role="button" id="hasLogin"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        คุณ ทดสอบ ทดสอบ
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="hasLogin">
                        <li><a class="dropdown-item" href="#" onclick="logout()">ออกจากระบบ</a></li>
                    </ul>
                </div>
            `;
        }
      })
      .catch((error) => {
        console.log("error", error);
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
