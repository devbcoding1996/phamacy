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

  await fetch("http://localhost:90/phamacy/APIs/drugType", requestOptions)
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

getPackage = () => {
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);

  var requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow",
  };

  fetch("http://localhost:90/phamacy/APIs/package", requestOptions)
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