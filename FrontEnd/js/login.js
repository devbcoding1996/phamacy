let role;
handleLogin = async () => {
  var _user = document.getElementById("login-form-username").value;
  var _pass = document.getElementById("login-form-password").value;
  try {
    var myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");
    myHeaders.append("Cookie", "isAdmin=true");

    var raw = JSON.stringify({
      email: _user,
      password: _pass,
    });

    var requestOptions = {
      method: "POST",
      headers: myHeaders,
      body: raw,
      redirect: "follow",
    };

    const response = await fetch("https://api.wakeupcoding.com/pharmacy-api/users/login", requestOptions)
      .then((result) => {
        return result.json();
      })
      .catch((error) => {
        return error;
      });
    if (response.message === "successfully") {
      localStorage.setItem("token", response.token);
      await checkLoginStatus();
    } else {
      Swal.fire({
        icon: "error",
        title: "คำเตือน",
        text: response.error || "Login failed",
      });
    }
  } catch (error) {
    console.error("Error:", error);
  }
};

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

  await fetch("https://api.wakeupcoding.com/pharmacy-api/users", requestOptions)
    .then(async (result) => {
      let response = await result.json();
      if (response.data) {
        if (response.data.isAdmin === "1") {
          setTimeout(() => {
            window.location.href = "admin/add.html";
          }, 1000);
        } else {
          window.location.reload();
        }
      } else {
        console.log(response.error);
      }
    })
    .catch((error) => {
      return error;
    });
};

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

  await fetch("https://api.wakeupcoding.com/pharmacy-api/users/logout", requestOptions)
    .then(async (result) => {
      let response = await result.json();
      localStorage.clear();
      Swal.fire({
        icon: "info",
        title: "กำลังออกจากระบบ",
        text: response.message,
      }).then((result) => {
        window.location.href = "index.html";
      });
    })
    .catch((error) => {
      console.log("error", error);
    });
};
