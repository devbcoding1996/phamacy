getDrugType = async () => {
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);

  var requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  await fetch("https://api.wakeupcoding.com/pharmacy-api/drugType", requestOptions)
    .then(async (result) => {
      let response = await result.json();
      console.log("response", response.drugType);
      $("#drugTypeId").empty();
      $("#drugTypeId").append(`<option selected disabled value="">เลือก...</option>`);
      response.drugType.forEach((element) => {
        $("#drugTypeId").append(`<option value="${element.id}">${element.name}</option>`);
      });
    })
    .catch((error) => {
      console.log("error", error);
    });
};
getDrugType();

getPackage = async () => {
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);

  var requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  await fetch("https://api.wakeupcoding.com/pharmacy-api/package", requestOptions)
    .then(async (result) => {
      let response = await result.json();
      console.log("response", response.package);
      $("#packageId").empty();
      $("#packageId").append(`<option selected disabled value="">เลือก...</option>`);
      response.package.forEach((element) => {
        $("#packageId").append(`<option value="${element.id}">${element.name}</option>`);
      });
    })
    .catch((error) => {
      console.log("error", error);
    });
};
getPackage();

getCategory = async () => {
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);

  var requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  await fetch("https://api.wakeupcoding.com/pharmacy-api/category", requestOptions)
    .then(async (result) => {
      let response = await result.json();
      console.log("response", response.category);
      $("#categoryId").empty();
      $("#categoryId").append(`<option selected disabled value="">เลือก...</option>`);
      response.category.forEach((element) => {
        $("#categoryId").append(`<option value="${element.id}">${element.name}</option>`);
      });
    })
    .catch((error) => {
      console.log("error", error);
    });
};
getCategory();
