callListCustomer = async () => {
  document.getElementById("noLogin").style.display = "block";
  document.getElementById("hasLogin").style.display = "none";

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

    await fetch("http://localhost:90/phamacy/APIs/userCustomer/listCustomerId/9", requestOptions)
      .then(async (result) => {
        let response = await result.json();
        console.log("response", response);
        if (response) {
          localStorage.setItem("customer_id", response.userCustomer.customer_id);
          document.getElementById("noLogin").style.display = "none";
          document.getElementById("hasLogin").style.display = "block";
        }
      })
      .catch((error) => {
        console.log("error", error);
      });
  }
};
callListCustomer();
