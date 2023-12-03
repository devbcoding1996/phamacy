checkLoginStatus = async () => {
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);
  myHeaders.append("Cookie", "isAdmin=true");

  var requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  await fetch("http://localhost:90/phamacy/APIs/users", requestOptions)
    .then(async (result) => {
      // return result.json()
      let response = await result.json();
      if (response.error) {
        Swal.fire({
          icon: "error",
          title: "เกิดข้อผิดพลาด",
          text: response.error,
        }).then((result) => {
          window.location.href = "../index.html";
        });
      } else {
        document.getElementById("admin").innerHTML = `สวัสดีแอดมิน ${response.data.name}`;
      }
    })
    .catch((error) => {
      return error;
    });
};
checkLoginStatus();

logout = async () => {
  var myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");

  var raw = "";

  var requestOptions = {
    method: "POST",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  await fetch("http://localhost:90/phamacy/APIs/users/logout", requestOptions)
    .then(async (result) => {
      let response = await result.json();
      localStorage.clear();
      Swal.fire({
        icon: "info",
        title: "กำลังออกจากระบบ",
        text: response.message,
      }).then((result) => {
        window.location.href = "../index.html";
      });
    })
    .catch((error) => {
      console.log("error", error);
    });
};
