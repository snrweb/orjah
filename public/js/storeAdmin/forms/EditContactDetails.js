import React, { Component } from "react";

class EditContactDetails extends Component {
  constructor(props) {
    super(props);
    this.state = {
      storeName: this.props.storeName,
      storeEmail: this.props.storeEmail,
      storeCategory: this.props.storeCategory,
      storeCountry: this.props.storeCountry,
      storeCity: this.props.storeCity,
      storeStreet: this.props.storeStreet,
      storePhone: this.props.storePhone
    };

    this.onFocus = this.onFocus.bind(this);
    this.onChange = this.onChange.bind(this);
    this.onSubmit = this.onSubmit.bind(this);
    this.alertMsg = this.alertMsg.bind(this);
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

  onChange(e) {
    this.setState({ [e.target.name]: e.target.value });
  }

  onSubmit(e) {
    e.preventDefault();
    let alertElem = document.querySelector("#alert");

    let formdata = new FormData();
    formdata.append("store_name", this.state.storeName);
    formdata.append("store_email", this.state.storeEmail);
    formdata.append("store_category", this.state.storeCategory);
    formdata.append("store_country", this.state.storeCountry);
    formdata.append("store_city", this.state.storeCity);
    formdata.append("store_street", this.state.storeStreet);
    formdata.append("store_phone", this.state.storePhone);

    fetch(`${this.props.root}stores/admin/editContact`, {
      method: "post",
      body: formdata
    })
      .then(res => res.text())
      .then(text => {
        if (text == "true") {
          this.alertMsg(
            alertElem,
            "success-alert",
            "Store details update successful"
          );
          this.props.resetState();
        } else {
          this.alertMsg(alertElem, "error-alert", "An error occurred");
        }
      })
      .catch(err => {
        console.log(err);
      });
  }

  render() {
    return (
      <form className="s-form" method="post" onSubmit={this.onSubmit}>
        <p className="s-formTitle">Store Details</p>
        <small id="alert" />
        <div className="input-wrapper">
          <label>Store name</label>
          <input
            type="text"
            placeholder="Store name"
            className="input"
            name="storeName"
            defaultValue={this.props.storeName}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>
        <div className="input-wrapper">
          <label>Email address</label>
          <input
            type="email"
            placeholder="Email"
            className="input"
            name="storeEmail"
            defaultValue={this.props.storeEmail}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>
        <div className="input-wrapper">
          <label>Phone number</label>
          <input
            type="tel"
            placeholder="Telephone"
            className="input"
            name="storePhone"
            defaultValue={this.props.storePhone}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>

        <div className="input-wrapper">
          <label>Category</label>
          <select
            className="input"
            name="storeCategory"
            defaultValue={this.props.storeCategory}
            onChange={this.onChange}
          >
            {this.props.categories.map((category, i) => (
              <option key={i}>{category}</option>
            ))}
          </select>
        </div>

        <p
          style={{
            marginTop: 20,
            marginLeft: 5,
            fontWeight: 600,
            fontSize: 20
          }}
        >
          Store location
        </p>
        <div className="input-wrapper">
          <label>Country</label>
          <input
            type="text"
            placeholder="Country"
            className="input"
            name="storeCountry"
            defaultValue={this.props.storeCountry}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>
        <div className="input-wrapper">
          <label>State</label>
          <input
            type="text"
            placeholder="City"
            className="input"
            name="storeCity"
            defaultValue={this.props.storeCity}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>
        <div className="input-wrapper">
          <label>Street</label>
          <input
            type="text"
            placeholder="Street address"
            className="input"
            name="storeStreet"
            defaultValue={this.props.storeStreet}
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

export default EditContactDetails;
