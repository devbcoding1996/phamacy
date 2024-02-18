var orderId; // Declaring a variable to store the order ID globally

orderDrugCreate = async () => {
  console.log("shoppingCart.totalCart()", shoppingCart.totalCart()); // Logging total items in the shopping cart
  if (shoppingCart.totalCart() > 0) { // Checking if there are items in the shopping cart
    var myHeaders = new Headers();
    let token = localStorage.getItem("token"); // Getting token from local storage
    let customerId = localStorage.getItem("customer_id"); // Getting customer ID from local storage

    if (!customerId) { // Checking if customer ID exists
      localStorage.clear(); // Clearing local storage
      shoppingCart.clearCart(); // Clearing the shopping cart
      shoppingCart.removeItemFromCartAll(); // Removing items from the shopping cart

      // Displaying a warning message using Swal (SweetAlert) if customer ID is not found
      Swal.fire({
        icon: "warning",
        title: "โปรดเข้าสู่ระบบ", // Warning title (in Thai)
        text: "Unauthorized, please, verify your token", // Warning message
      }).then((result) => {
        return false; // Returning false to indicate failure
      });
    }

    // Setting up request headers
    myHeaders.append("Content-Type", "application/json");
    myHeaders.append("Authorization", `Bearer ${token}`);

    // Creating a JSON string with customer ID and total cart value
    var raw = JSON.stringify({
      customerId: customerId,
      total: shoppingCart.totalCart(),
    });

    // Setting up request options
    var requestOptions = {
      method: "POST",
      headers: myHeaders,
      body: raw,
      redirect: "follow",
    };

    // Sending a POST request to create an order
    await fetch("https://api.wakeupcoding.com/pharmacy-api/orderDrug/create", requestOptions)
      .then(async (result) => {
        // Parsing response JSON
        let response = await result.json();
        console.log("response", response);
        if (response.orderId) {
          orderId = response.orderId; // Storing the order ID globally
          await localStorage.setItem("orderId", response.orderId); // Storing the order ID in local storage
          await displayCart(); // Displaying the cart
          document.getElementById("payment").disabled = false; // Enabling payment button
        }
      })
      .catch((error) => {
        console.log("error", error); // Logging any errors to console
      });
  } else {
    // Displaying a warning message if the shopping cart is empty
    Swal.fire({
      icon: "warning",
      title: "คำเตือน", // Warning title (in Thai)
      text: "โปรดเพิ่มสินค้นในตะกร้า", // Warning message
    });
  }
};

orderDrugDetailCreate = async () => {
  var myHeaders = new Headers();
  let token = localStorage.getItem("token");

  // Setting up request headers
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${token}`);

  // Displaying loading spinner
  document.getElementById("load").style.display = "flex";

  // Retrieving cart items from shopping cart
  var cartArray = shoppingCart.listCart();

  // Mapping cart items to create details for the order
  let model = cartArray.map(function (item) {
    return {
      drugInfoId: item.drugInfoId,
      orderId: item.orderId,
      quantity: item.count,
      value: item.price,
      total: item.total,
    };
  });

  // Converting details model to JSON string
  var raw = JSON.stringify(model);

  // Setting up request options
  var requestOptions = {
    method: "POST",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  // Sending a POST request to create order details
  await fetch("https://api.wakeupcoding.com/pharmacy-api/orderDrugDetail/create", requestOptions)
    .then(async (result) => {
      // Parsing response JSON
      let response = await result.json();
      console.log("response", response);
      // Redirecting to payment page after a delay and clearing the shopping cart
      setTimeout(() => {
        window.location.href = `payment.html?orderId=${orderId}`;
        shoppingCart.clearCart();
        shoppingCart.removeItemFromCartAll();
      }, 2000);
    })
    .catch((error) => {
      console.log("error", error); // Logging any errors to console
    });
};

var shoppingCart = (function () {
  cart = [];

  function Item(name, price, count, drugInfoId) {
    // Initializing properties for the item
    this.name = name; // Name of the item
    this.price = price; // Price of the item
    this.count = count; // Quantity of the item
    this.drugInfoId = drugInfoId; // Identifier for the drug information
  }  

  // Save cart
  function saveCart() {
    // Convert the cart array to a JSON string and store it in the "shoppingCart" key of localStorage
    localStorage.setItem("shoppingCart", JSON.stringify(cart));
  }  

  // Load cart
  function loadCart() {
    // Retrieve the saved shopping cart data from localStorage and parse it back into a JavaScript object
    cart = JSON.parse(localStorage.getItem("shoppingCart"));
  }
  // Check if there's any saved shopping cart data in localStorage
  if (localStorage.getItem("shoppingCart") != null) {
    // If shopping cart data exists, load it into the cart array
    loadCart();
  }

  var obj = {};

  // Add to cart
  obj.addItemToCart = function (name, price, count, id) {
    // Loop through the items in the cart
    for (var item in cart) {
      // Check if an item with the same name already exists in the cart
      if (cart[item].name === name) {
        // If the item exists, increase its count by 1
        cart[item].count++;
        // Save the updated cart to localStorage
        saveCart();
        // Exit the function since the item has been found and updated
        return;
      }
    }
    // If the item is not found in the cart, create a new item object
    var item = new Item(name, price, count, id);
    // Add the new item to the cart array
    cart.push(item);
    // Save the updated cart to localStorage
    saveCart();
  };
  
  // Set count from item
  obj.setCountForItem = function (name, count) {
    // Loop through the items in the cart
    for (var i in cart) {
      // Check if the name of the current item matches the provided name
      if (cart[i].name === name) {
        // If a match is found, update the count of the item to the provided count
        cart[i].count = count;
        // Exit the loop since the count has been updated for the item
        break;
      }
    }
    // Save the updated cart to localStorage
    saveCart();
  };
  // Set orderid from item
  obj.setOrderIdForItem = function (orderId) {
    // Loop through the items in the cart
    for (var i in cart) {
      // Set the orderId property of each item to the provided orderId
      cart[i].orderId = orderId;
    }
    // Save the updated cart to localStorage
    saveCart();
  };
  // Remove item from cart
  obj.removeItemFromCart = function (name) {
    // Loop through the items in the cart
    for (var item in cart) {
      // Check if the name of the current item matches the provided name
      if (cart[item].name === name) {
        // Decrement the count of the item by 1
        cart[item].count--;
        // If the count becomes zero, remove the item from the cart
        if (cart[item].count === 0) {
          cart.splice(item, 1);
        }
        // Exit the loop since the item has been found and handled
        break;
      }
    }
    // Save the updated cart to localStorage
    saveCart();
  };

  // Remove all items from cart
  obj.removeItemFromCartAll = function (name) {
    // Log the name of the item to be removed
    console.log("remove", name);
    
    // Loop through the items in the cart
    for (var item in cart) {
      // Check if the name of the current item matches the provided name
      if (cart[item].name === name) {
        // Remove the item from the cart using splice
        cart.splice(item, 1);
        // Exit the loop since the item has been found and removed
        break;
      }
    }
    // Save the updated cart to localStorage
    saveCart();
  };

  // Clear cart
  obj.clearCart = function () {
    // Set the cart array to an empty array, effectively clearing all items
    cart = [];
    // Save the updated (empty) cart to localStorage
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
  // Prevent the default behavior of the event (e.g., form submission or link click)
  event.preventDefault();

  // Extract relevant information from the event target's attributes
  var id = event.target.attributes["data-id"].value;
  var name = event.target.attributes["data-name"].value;
  var price = Number(event.target.attributes["data-price"].value);

  // Log the name and price of the item for debugging purposes
  console.log("name", name);
  console.log("price", price);

  // Call the addItemToCart method of the shoppingCart object to add the item to the cart
  // Pass the extracted name, price, quantity (1 in this case), and id to the method
  shoppingCart.addItemToCart(name, price, 1, id);

  // Call the displayCart function to update the display of the shopping cart
  displayCart();
}

function updateItem(name, event) {
  // Extract the name of the item from the function parameter
  var name = name;

  // Extract the new quantity of the item from the event value (presumably an input field)
  var count = Number(event.value);

  // Call the setCountForItem method of the shoppingCart object to update the count of the item
  // Pass the item's name and the new count as arguments
  shoppingCart.setCountForItem(name, count);

  // Call the setOrderIdForItem method of the shoppingCart object to update the orderId of the item
  // Pass the item's name and the orderId retrieved from localStorage as arguments
  shoppingCart.setOrderIdForItem(name, localStorage.getItem("orderId"));

  // Call the displayCart function to update the display of the shopping cart
  displayCart();
}

// Delete item button
function deleteItem(event) {
  // Extract the name of the item from the data-name attribute of the event target
  var name = event.target.attributes["data-name"].value;

  // Log the name of the item for debugging purposes
  console.log("name", name);

  // Call the removeItemFromCartAll method of the shoppingCart object to remove all occurrences of the item from the cart
  // Pass the item's name as an argument
  shoppingCart.removeItemFromCartAll(name);

  // Call the displayCart function to update the display of the shopping cart
  displayCart();
}

// Clear items
$(".clear-cart").click(function () {
  // Call the clearCart method of the shoppingCart object to clear the cart
  shoppingCart.clearCart();

  // Call the displayCart function to update the display of the shopping cart
  displayCart();
});

function displayCart() {
  // Set the orderId for each item in the cart
  shoppingCart.setOrderIdForItem(localStorage.getItem("orderId"));

  // Retrieve the list of items in the cart
  var cartArray = shoppingCart.listCart();

  // Initialize an empty string to store the HTML output
  var output = "";

  // Iterate through each item in the cartArray
  for (var i in cartArray) {
    // Construct HTML markup for each item and append it to the output string
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

  // Set the HTML content of elements with class 'show-cart' to the generated output
  $(".show-cart").html(output);

  // Display the total cart value and total count of items in the cart
  $(".total-cart").html(`${shoppingCart.totalCart()}฿`);
  $(".total-count").html(shoppingCart.totalCount());
}

// Item count input
$(".show-cart").on("change", ".item-count", function (event) {
  // Extract the name of the item from the data-name attribute of the input element that triggered the change
  var name = $(this).data("name");

  // Extract the new quantity of the item from the value of the input element
  var count = Number($(this).val());

  // Call the setCountForItem method of the shoppingCart object to update the count of the item
  // Pass the item's name and the new count as arguments
  shoppingCart.setCountForItem(name, count);

  // Call the displayCart function to update the display of the shopping cart
  displayCart();
});

// Initially display the contents of the shopping cart when the page loads
displayCart();

//////// ui script start /////////
// Tabs Single Page
// Add "active" class to the first tab and "current" class to its corresponding list item
$(".tab ul.tabs").addClass("active").find("> li:eq(0)").addClass("current");

// Attach a click event handler to anchor elements within list items
$(".tab ul.tabs li a").on("click", function (g) {
  // Find the closest parent element with class "tab"
  var tab = $(this).closest(".tab");

  // Get the index of the clicked list item
  var index = $(this).closest("li").index();

  // Remove the "current" class from all list items within the tab
  tab.find("ul.tabs > li").removeClass("current");

  // Add the "current" class to the clicked list item
  $(this).closest("li").addClass("current");

  // Hide all tab content items within the tab except for the one at the clicked index
  tab.find(".tab_content").find("div.tabs_item").not("div.tabs_item:eq(" + index + ")").slideUp();

  // Show the tab content item corresponding to the clicked index
  tab.find(".tab_content").find("div.tabs_item:eq(" + index + ")").slideDown();

  // Prevent the default anchor link behavior
  g.preventDefault();
});


// search function
$("#search_field").on("keyup", function () {
  // Retrieve the value entered in the search input field
  var value = $(this).val();

  // Create a regular expression pattern with the entered value (case-insensitive)
  var patt = new RegExp(value, "i");

  // Iterate over each element with class "col-lg-3" within elements with class "tab_content"
  $(".tab_content").find(".col-lg-3").each(function () {
    var $table = $(this);

    // Check if the text of the element contains the search pattern
    if (!($table.find(".featured-item").text().search(patt) >= 0)) {
      // If not found, hide the element
      $table.not(".featured-item").hide();
    }

    // Check if any descendant element's text matches the search pattern
    if ($table.find(".col-lg-3").text().search(patt) >= 0) {
      // If found, show the element and hide the "not_found" message
      $(this).show();
      document.getElementById("not_found").style.display = "none";
    } else {
      // If not found, display the "not_found" message
      document.getElementById("not_found").innerHTML = " Product not found..";
      document.getElementById("not_found").style.display = "block";
    }
  });
});
