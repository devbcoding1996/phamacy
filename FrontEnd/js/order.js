orderDrugCreate = async () => {
  console.log("shoppingCart.totalCart()", shoppingCart.totalCart());
  if (shoppingCart.totalCart() > 0) {
    var myHeaders = new Headers();
    let token = localStorage.getItem("token");
    let customerId = localStorage.getItem("customer_id");
    if (!customerId) {
      Swal.fire({
        icon: "warning",
        title: "โปรดเข้าสู่ระบบ",
        text: "Unauthorized, please, verify your token",
      }).then((result) => {
        return false;
      });
    }
    myHeaders.append("Content-Type", "application/json");
    myHeaders.append("Authorization", `Bearer ${token}`);

    var raw = JSON.stringify({
      customerId: customerId,
      total: shoppingCart.totalCart(),
    });

    var requestOptions = {
      method: "POST",
      headers: myHeaders,
      body: raw,
      redirect: "follow",
    };
    await fetch("https://api.wakeupcoding.com/pharmacy-api/orderDrug/create", requestOptions)
      .then(async (result) => {
        let response = await result.json();
        console.log("response", response);
        if (response.orderId) {
          localStorage.setItem("orderId", response.orderId);
        }
      })
      .catch((error) => {
        console.log("error", error);
      });
  } 
  // else {
  //   Swal.fire({
  //     icon: "warning",
  //     title: "คำเตือน",
  //     text: "โปรดเพิ่มสินค้นในตะกร้า",
  //   });
  // }
};
document.getElementById("top-cart-trigger").addEventListener("click", orderDrugCreate);

orderDrugDetailCreate = async () => {
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);
  document.getElementById("load").style.display = "flex";

  var cartArray = shoppingCart.listCart();
  let model = cartArray.map(function (item) {
    return {
      drugInfoId: item.drugInfoId,
      orderId: item.orderId,
      quantity: item.count,
      value: item.price,
      total: item.total,
    };
  });

  var raw = JSON.stringify(model);

  var requestOptions = {
    method: "POST",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  await fetch("https://api.wakeupcoding.com/pharmacy-api/orderDrugDetail/create", requestOptions)
    .then(async (result) => {
      let response = await result.json();
      console.log("response", response);
      setTimeout(() => {
        window.location.href = "payment.html";
      }, 2000);
    })
    .catch((error) => {
      console.log("error", error);
    });
};

var shoppingCart = (function () {
  cart = [];

  function Item(name, price, count, drugInfoId) {
    this.name = name;
    this.price = price;
    this.count = count;
    this.drugInfoId = drugInfoId;
  }

  // Save cart
  function saveCart() {
    localStorage.setItem("shoppingCart", JSON.stringify(cart));
  }

  // Load cart
  function loadCart() {
    cart = JSON.parse(localStorage.getItem("shoppingCart"));
  }
  if (localStorage.getItem("shoppingCart") != null) {
    loadCart();
  }

  var obj = {};

  // Add to cart
  obj.addItemToCart = function (name, price, count, id) {
    for (var item in cart) {
      if (cart[item].name === name) {
        cart[item].count++;
        saveCart();
        return;
      }
    }
    var item = new Item(name, price, count, id);
    cart.push(item);
    saveCart();
  };
  // Set count from item
  obj.setCountForItem = function (name, count) {
    for (var i in cart) {
      if (cart[i].name === name) {
        cart[i].count = count;
        break;
      }
    }
    saveCart();
  };
  // Set orderid from item
  obj.setOrderIdForItem = function (orderId) {
    for (var i in cart) {
      cart[i].orderId = orderId;
    }
    saveCart();
  };
  // Remove item from cart
  obj.removeItemFromCart = function (name) {
    for (var item in cart) {
      if (cart[item].name === name) {
        cart[item].count--;
        if (cart[item].count === 0) {
          cart.splice(item, 1);
        }
        break;
      }
    }
    saveCart();
  };

  // Remove all items from cart
  obj.removeItemFromCartAll = function (name) {
    console.log("remove", name);
    for (var item in cart) {
      if (cart[item].name === name) {
        cart.splice(item, 1);
        break;
      }
    }
    saveCart();
  };

  // Clear cart
  obj.clearCart = function () {
    cart = [];
    saveCart();
  };

  // Count cart
  obj.totalCount = function () {
    var totalCount = 0;
    for (var item in cart) {
      totalCount += cart[item].count;
    }
    return totalCount;
  };

  // Total cart
  obj.totalCart = function () {
    var totalCart = 0;
    for (var item in cart) {
      totalCart += cart[item].price * cart[item].count;
    }
    return Number(totalCart.toFixed(2));
  };

  // List cart
  obj.listCart = function () {
    var cartCopy = [];
    for (i in cart) {
      item = cart[i];
      itemCopy = {};
      for (p in item) {
        itemCopy[p] = item[p];
      }
      itemCopy.total = Number(item.price * item.count).toFixed(2);
      cartCopy.push(itemCopy);
    }
    return cartCopy;
  };
  return obj;
})();

function addItem(event) {
  // alert('working');
  event.preventDefault();
  var id = event.target.attributes["data-id"].value;
  var name = event.target.attributes["data-name"].value;
  var price = Number(event.target.attributes["data-price"].value);
  console.log("name", name);
  console.log("price", price);
  shoppingCart.addItemToCart(name, price, 1, id);
  displayCart();
}

function updateItem(name, event) {
  var name = name;
  var count = Number(event.value);
  shoppingCart.setCountForItem(name, count);
  shoppingCart.setOrderIdForItem(name, localStorage.getItem("orderId"));
  displayCart();
}

// Delete item button
function deleteItem(event) {
  var name = event.target.attributes["data-name"].value;
  console.log("name", name);
  shoppingCart.removeItemFromCartAll(name);
  displayCart();
}

// Clear items
$(".clear-cart").click(function () {
  shoppingCart.clearCart();
  displayCart();
});

function displayCart() {
  orderDrugCreate();
  shoppingCart.setOrderIdForItem(localStorage.getItem("orderId"));
  var cartArray = shoppingCart.listCart();
  var output = "";
  console.log("cartArray[i].name", cartArray);
  for (var i in cartArray) {
    output += `
      <tr>
          <td>${cartArray[i].name}</td>
          <td>(${cartArray[i].price}฿)</td>
          <td>
              <div class='input-group'>
                  <input type='number' class='item-count form-control' data-name='${cartArray[i].name}' onchange='updateItem("${cartArray[i].name}", this)'
                      value='${cartArray[i].count}'>
              </div>
          </td>
          <td><button class='delete-item btn btn-danger' onclick='deleteItem(event)' data-name='${cartArray[i].name}'>X</button>
          </td>
      </tr>
    `;
  }
  $(".show-cart").html(output);
  $(".total-cart").html(shoppingCart.totalCart());
  $(".total-count").html(shoppingCart.totalCount());
}

// Item count input
$(".show-cart").on("change", ".item-count", function (event) {
  var name = $(this).data("name");
  var count = Number($(this).val());
  shoppingCart.setCountForItem(name, count);
  displayCart();
});
displayCart();

//////// ui script start /////////
// Tabs Single Page
$(".tab ul.tabs").addClass("active").find("> li:eq(0)").addClass("current");
$(".tab ul.tabs li a").on("click", function (g) {
  var tab = $(this).closest(".tab"),
    index = $(this).closest("li").index();
  tab.find("ul.tabs > li").removeClass("current");
  $(this).closest("li").addClass("current");
  tab
    .find(".tab_content")
    .find("div.tabs_item")
    .not("div.tabs_item:eq(" + index + ")")
    .slideUp();
  tab
    .find(".tab_content")
    .find("div.tabs_item:eq(" + index + ")")
    .slideDown();
  g.preventDefault();
});

// search function
$("#search_field").on("keyup", function () {
  var value = $(this).val();
  var patt = new RegExp(value, "i");

  $(".tab_content")
    .find(".col-lg-3")
    .each(function () {
      var $table = $(this);

      if (!($table.find(".featured-item").text().search(patt) >= 0)) {
        $table.not(".featured-item").hide();
      }
      if ($table.find(".col-lg-3").text().search(patt) >= 0) {
        $(this).show();
        document.getElementById("not_found").style.display = "none";
      } else {
        document.getElementById("not_found").innerHTML = " Product not found..";
        document.getElementById("not_found").style.display = "block";
      }
    });
});
