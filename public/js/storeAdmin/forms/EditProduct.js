import React, { Component } from "react";

import Mark from "./../../../images/svg/mark.svg";

class EditProduct extends Component {
  constructor(props) {
    super(props);

    this.state = {
      storeDetails: {},
      product: {},
      categories: [],
      subCat: [],
      productName: "",
      productPrice: 0,
      storeMenu: "",
      selectedCat: "",
      selectedSubCat: "",
      imageOne: {},
      imageTwo: {},
      imageThree: {},
      productDetails: ""
    };

    this.onFocus = this.onFocus.bind(this);
    this.onChange = this.onChange.bind(this);
    this.getCat = this.getCat.bind(this);
    this.getSubCat = this.getSubCat.bind(this);
    this.getStoreMenu = this.getStoreMenu.bind(this);

    this.onChangeImageOne = this.onChangeImageOne.bind(this);
    this.onChangeImageTwo = this.onChangeImageTwo.bind(this);
    this.onChangeImageThree = this.onChangeImageThree.bind(this);

    this.onSubmit = this.onSubmit.bind(this);
    this.alertMsg = this.alertMsg.bind(this);
    this.validateImage = this.validateImage.bind(this);
    this.previewImage = this.previewImage.bind(this);
    this.enableImageSelect = this.enableImageSelect.bind(this);
  }

  componentDidMount() {
    fetch(
      `${this.props.root}stores/admin/modifyProduct/${
        this.props.match.params.productId
      }`
    )
      .then(res => res.json())
      .then(res => {
        this.setState({
          storeDetails: res.store_details,
          product: res.product,
          categories: res.categories,
          productName: res.product.product_name,
          productPrice: res.product.product_price,
          storeMenu: res.product.store_menu,
          selectedCat: res.product.product_cat,
          selectedSubCat: res.product.product_sub_cat,
          productDetails: res.product.product_details,
          subCat: res.subCat
        });
        document.querySelector(`.textarea`).value = res.product.product_details;
      });
  }

  getStoreMenu(e) {
    e.preventDefault();
    const elems = document.getElementsByClassName("s-storeMenu-options").length;
    for (let i = 0; i < elems; i++) {
      document.querySelectorAll(".s-storeMenu-options")[i].style.background =
        "";
      document.querySelectorAll(".s-storeMenu-options")[i].style.color = "";
      document.querySelectorAll(".s-storeMenu-options-mark")[i].style.display =
        "";
    }

    this.setState({ storeMenu: e.target.getAttribute("data-name") });

    e.target.style.background = "#7f70b4";
    e.target.style.color = "white";
    e.target.querySelector(".s-storeMenu-options-mark").style.display =
      "inline";
  }

  getCat(e) {
    e.preventDefault();
    const elems = document.getElementsByClassName("s-catOPtions").length;
    for (let i = 0; i < elems; i++) {
      document.querySelectorAll(".s-catOPtions")[i].style.background = "";
      document.querySelectorAll(".s-catOPtions")[i].style.color = "";
      document.querySelectorAll(".s-catOPtions-mark")[i].style.display = "";
    }

    this.setState({ selectedCat: e.target.getAttribute("data-name") });

    if (
      Object.values(
        this.state.categories[this.state.storeDetails.store_category][
          e.target.getAttribute("data-name")
        ]
      ) != undefined
    ) {
      this.setState({
        subCat: Object.values(
          this.state.categories[this.state.storeDetails.store_category][
            e.target.getAttribute("data-name")
          ]
        )
      });
    }

    e.target.style.background = "#7f70b4";
    e.target.style.color = "white";
    e.target.querySelector(".s-catOPtions-mark").style.display = "inline";
  }

  getSubCat(e) {
    e.preventDefault();
    const elems = document.getElementsByClassName("s-scatOPtions").length;
    for (let i = 0; i < elems; i++) {
      document.querySelectorAll(".s-scatOPtions")[i].style.background = "";
      document.querySelectorAll(".s-scatOPtions")[i].style.color = "";
      document.querySelectorAll(".s-scatOPtions-mark")[i].style.display = "";
    }

    this.setState({ selectedSubCat: e.target.getAttribute("data-name") });

    e.target.style.background = "#7f70b4";
    e.target.style.color = "white";
    e.target.querySelector(".s-scatOPtions-mark").style.display = "inline";
  }

  alertMsg(elem, className, errorMessage) {
    elem.classList.add(className);
    elem.innerHTML = errorMessage;
  }

  onFocus() {
    let alertElem = document.querySelector("#alert");

    if (alertElem.classList.contains("error-alert")) {
      alertElem.classList.remove("error-alert");
    }

    if (alertElem.classList.contains("success-alert")) {
      alertElem.classList.remove("success-alert");
    }
    alertElem.innerHTML = "";
  }

  validateImage(image) {
    let alertElem = document.querySelector("#alert");
    if (image.name == "") {
      this.alertMsg(alertElem, "error-alert", "Please select an image");
      this.setState({ hasError: true });
      return false;
    }

    let extension = image.type
      .split("/")
      .pop()
      .toLowerCase();
    if (["jpg", "png", "jpeg"].indexOf(extension) == -1) {
      this.alertMsg(
        alertElem,
        "error-alert",
        "The selected file is not an image"
      );
      return false;
    }

    if (image.size > 4500000) {
      this.alertMsg(
        alertElem,
        "error-alert",
        "Image should not be more than 4mb"
      );
      return false;
    }
    return true;
  }

  enableImageSelect(number) {
    document.querySelector(
      `.s-editProduct-input-file-w${number}`
    ).style.background = "white";
    document.querySelector(
      `.s-editProduct-input-file-w${number} button`
    ).style.background = "#523f96";

    document
      .querySelector(`.s-editProduct-input-file-w${number} input`)
      .removeAttribute("disabled");
  }

  previewImage(image, number) {
    document.querySelector(`#s-editProduct-btn-${number}`).innerHTML =
      image.name;
    let preview = document.querySelector(`#s-editProduct-img-${number}`);
    preview.style.display = "block";
    preview.src = URL.createObjectURL(image);
  }

  onChange(e) {
    this.setState({ [e.target.name]: e.target.value });
  }

  onChangeImageOne(e) {
    let image = e.target.files[0];
    if (this.validateImage(image)) {
      this.enableImageSelect(2);
      this.setState({ imageOne: image });
      this.previewImage(image, 1);
    }
  }

  onChangeImageTwo(e) {
    let image = e.target.files[0];
    if (this.validateImage(image)) {
      this.enableImageSelect(3);
      this.setState({ imageTwo: image });
      this.previewImage(image, 2);
    }
  }

  onChangeImageThree(e) {
    let image = e.target.files[0];
    if (this.validateImage(image)) {
      this.setState({ imageThree: image });
      this.previewImage(image, 3);
    }
  }

  onSubmit(e) {
    e.preventDefault();
    let alertElem = document.querySelector("#alert");

    if (this.state.productName.trim() == "") {
      this.alertMsg(alertElem, "error-alert", "Product name is empty");
      return;
    }

    const price = String(this.state.product.product_price);

    if (price == "0" || !price.match(/^[0-9]+$/)) {
      this.alertMsg(
        alertElem,
        "error-alert",
        "Price should only contain numbers"
      );
      return;
    }

    if (this.state.selectedCat.trim() == "") {
      this.alertMsg(
        alertElem,
        "error-alert",
        "Select a category for your product"
      );
      return;
    }

    if (this.state.selectedSubCat.trim() == "") {
      this.alertMsg(
        alertElem,
        "error-alert",
        "Select a sub category for your product"
      );
      return;
    }

    if (
      this.state.imageOne.name == undefined &&
      this.state.product.product_image_one == ""
    ) {
      this.alertMsg(
        alertElem,
        "error-alert",
        "You have not selected any image"
      );
      return;
    }

    if (this.state.productDetails.trim() == "") {
      this.alertMsg(alertElem, "error-alert", "Product detail is required");
      return;
    }

    let formdata = new FormData();
    formdata.append("product_name", this.state.productName);
    formdata.append("product_price", this.state.productPrice);
    formdata.append("store_menu", this.state.storeMenu);
    formdata.append("product_cat", this.state.selectedCat);
    formdata.append("product_sub_cat", this.state.selectedSubCat);
    formdata.append("product_image_one", this.state.imageOne);
    formdata.append("product_image_two", this.state.imageTwo);
    formdata.append("product_image_three", this.state.imageThree);
    formdata.append(
      "product_image_one_o",
      this.state.product.product_image_one
    );
    formdata.append(
      "product_image_two_o",
      this.state.product.product_image_two
    );
    formdata.append(
      "product_image_three_o",
      this.state.product.product_image_three
    );
    formdata.append("product_details", this.state.productDetails);

    fetch(
      `${this.props.root}stores/admin/modifyProduct/${
        this.props.match.params.productId
      }`,
      {
        method: "post",
        body: formdata
      }
    )
      .then(res => res.text())
      .then(text => {
        console.log(text);
        if (text == "true") {
          this.props.resetState(true);
          this.alertMsg(alertElem, "success-alert", "Product added to store");
        } else {
          this.alertMsg(alertElem, "error-alert", "An error occurred");
        }
      })
      .catch(err => {
        console.log(err);
      });
  }

  render() {
    const selectedStyle = {
      color: "white",
      background: "#7f70b4"
    };

    return (
      <form
        className="s-form"
        method="post"
        encType="multipart/form-data"
        onSubmit={this.onSubmit}
      >
        <p className="s-formTitle">Update Product</p>
        <small id="alert" />

        <div className="input-wrapper">
          <label>Product name</label>
          <input
            type="text"
            name="productName"
            defaultValue={this.state.product.product_name}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>

        <div className="input-wrapper">
          <label>Product price</label>
          <input
            type="text"
            name="productPrice"
            defaultValue={this.state.product.product_price}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>

        <div className="input-wrapper">
          <label>Preferred store menu</label>
          {this.state.storeDetails.category_one != "" && (
            <button
              className="btn s-storeMenu-options"
              style={
                this.state.storeDetails.category_one == this.state.storeMenu
                  ? selectedStyle
                  : {}
              }
              key={1}
              onClick={this.getStoreMenu}
              data-name={this.state.storeDetails.category_one}
            >
              {this.state.storeDetails.category_one}
              <span className="s-storeMenu-options-mark">
                <Mark />
              </span>
            </button>
          )}

          {this.state.storeDetails.category_two != "" && (
            <button
              className="btn s-storeMenu-options"
              style={
                this.state.storeDetails.category_two == this.state.storeMenu
                  ? selectedStyle
                  : {}
              }
              key={2}
              onClick={this.getStoreMenu}
              data-name={this.state.storeDetails.category_two}
            >
              {this.state.storeDetails.category_two}
              <span className="s-storeMenu-options-mark">
                <Mark />
              </span>
            </button>
          )}

          {this.state.storeDetails.category_three != "" && (
            <button
              className="btn s-storeMenu-options"
              style={
                this.state.storeDetails.category_three == this.state.storeMenu
                  ? selectedStyle
                  : {}
              }
              key={3}
              onClick={this.getStoreMenu}
              data-name={this.state.storeDetails.category_three}
            >
              {this.state.storeDetails.category_three}
              <span className="s-storeMenu-options-mark">
                <Mark />
              </span>
            </button>
          )}

          {this.state.storeDetails.category_four != "" && (
            <button
              className="btn s-storeMenu-options"
              style={
                this.state.storeDetails.category_four == this.state.storeMenu
                  ? selectedStyle
                  : {}
              }
              key={4}
              onClick={this.getStoreMenu}
              data-name={this.state.storeDetails.category_four}
            >
              {this.state.storeDetails.category_four}
              <span className="s-storeMenu-options-mark">
                <Mark />
              </span>
            </button>
          )}
        </div>

        <div className="input-wrapper">
          <label>Select product category</label>
          {this.state.categories.length != 0 &&
            Object.keys(
              this.state.categories[this.state.storeDetails.store_category]
            ).map((cat, i) => (
              <button
                className="btn s-catOPtions"
                key={i}
                onClick={this.getCat}
                data-name={cat}
                style={
                  cat == this.state.product.product_cat ? selectedStyle : {}
                }
              >
                {cat}
                <span className="s-catOPtions-mark">
                  <Mark />
                </span>
              </button>
            ))}
        </div>

        <div className="input-wrapper">
          {this.state.subCat.length != 0 && (
            <label>Select other product category</label>
          )}
          {this.state.subCat.length != 0 &&
            this.state.subCat.map((scat, i) => (
              <button
                className="btn s-scatOPtions"
                key={i}
                onClick={this.getSubCat}
                data-name={scat}
                style={
                  scat == this.state.product.product_sub_cat
                    ? selectedStyle
                    : {}
                }
              >
                {scat}
                <span className="s-scatOPtions-mark">
                  <Mark />
                </span>
              </button>
            ))}
        </div>

        <div className="input-file s-editProduct-input-file s-editProduct-input-file-w1">
          <label htmlFor="s-editProduct-input-file-1" />
          <input
            type="file"
            id="s-editProduct-input-file-1"
            name="product_image_one"
            onChange={this.onChangeImageOne}
          />
          <img
            src={`${this.props.uroot}public/images/products/${
              this.state.product.product_image_one
            }`}
            id="s-editProduct-img-1"
          />
          <button className="btn" id="s-editProduct-btn-1">
            Image 1
          </button>
        </div>
        <input
          readOnly
          type="hidden"
          name="product_image_one_o"
          defaultValue={this.state.product.product_image_one}
        />

        <div className="input-file s-editProduct-input-file s-editProduct-input-file-w2">
          <label htmlFor="s-editProduct-input-file-2" />
          <input
            type="file"
            id="s-editProduct-input-file-2"
            name="product_image_two"
            onChange={this.onChangeImageTwo}
          />
          <img
            src={`${this.props.uroot}public/images/products/${
              this.state.product.product_image_two
            }`}
            id="s-editProduct-img-2"
          />
          <button className="btn" id="s-editProduct-btn-2">
            Image 2
          </button>
        </div>
        <input
          readOnly
          type="hidden"
          name="product_image_two_o"
          defaultValue={this.state.product.product_image_two}
        />

        <div
          className="input-file s-editProduct-input-file s-editProduct-input-file-w3"
          style={{ marginBottom: 20 + "px" }}
        >
          <label htmlFor="s-editProduct-input-file-3" />
          <input
            type="file"
            id="s-editProduct-input-file-3"
            name="product_image_three"
            onChange={this.onChangeImageThree}
          />
          <img
            src={`${this.props.uroot}public/images/products/${
              this.state.product.product_image_three
            }`}
            id="s-editProduct-img-3"
          />
          <button className="btn" id="s-editProduct-btn-3">
            Image 3
          </button>
        </div>
        <input
          readOnly
          type="hidden"
          name="product_image_three_o"
          defaultValue={this.state.product.product_image_three}
        />
        <div className="ClearFloat" />

        <div className="input-wrapper">
          <label>Prouduct details</label>
          <textarea
            className="textarea"
            rows="8"
            name="productDetails"
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>

        <div className="input-wrapper">
          <input type="submit" value="Submit" className="btn s-submitbtn" />
        </div>
      </form>
    );
  }
}

export default EditProduct;
