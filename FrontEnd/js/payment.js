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
