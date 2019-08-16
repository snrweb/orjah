import React, { Component } from "react";

class UpdateDelivery extends Component {
  constructor(props) {
    super(props);
    this.state = {
      deliveryTerms: this.props.deliveryTerms,
      returnPolicy: this.props.returnPolicy
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
    formdata.append("delivery_terms", this.state.deliveryTerms);
    formdata.append("return_policy", this.state.returnPolicy);

    fetch(`${this.props.root}stores/admin/editDelivery`, {
      method: "post",
      body: formdata
    })
      .then(res => res.text())
      .then(text => {
        if (text == "true") {
          this.props.resetState();
          this.alertMsg(
            alertElem,
            "success-alert",
            "Store policies/terms update successful"
          );
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
        <p className="s-formTitle">Delivery and Return Policy</p>
        <small id="alert" />

        <div className="input-wrapper">
          <label>Delivery terms</label>
          <textarea
            type="url"
            className="textarea"
            placeholder="Delivery terms"
            name="deliveryTerms"
            rows="5"
            defaultValue={this.props.deliveryTerms}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>
        <div className="input-wrapper">
          <label>Return policy</label>
          <textarea
            type="url"
            className="textarea"
            placeholder="Return policy"
            name="returnPolicy"
            rows="5"
            defaultValue={this.props.returnPolicy}
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

export default UpdateDelivery;
