import React, { Component } from "react";

class CoverPhoto extends Component {
  constructor(props) {
    super(props);

    this.alertError = this.alertError.bind(this);
    this.onChange = this.onChange.bind(this);
  }

  alertError(elem, className, errorMessage) {
    elem.classList.add(className);
    elem.innerHTML = errorMessage;
  }

  onChange() {
    let image = document.querySelector("#coverPhoto-input").files[0];
    let alertElem = document.querySelector("#alert");

    if (alertElem.classList.contains("error-alert")) {
      alertElem.classList.remove("error-alert");
    }

    alertElem.innerHTML = "";

    if (image.name == "") {
      this.alertError(alertElem, "error-alert", "Please select an image");
      return;
    }

    let extension = image.name
      .split(".")
      .pop()
      .toLowerCase();
    if (["jpg", "png", "jpeg"].indexOf(extension) == -1) {
      this.alertError(
        alertElem,
        "error-alert",
        "The selected file is not an image"
      );
      return;
    }

    if (image.size > 4200000) {
      this.alertError(
        alertElem,
        "error-alert",
        "Image should not be more than 4mb"
      );
      return;
    }

    let formdata = new FormData();
    formdata.append("store_coverPhoto", image);

    fetch(`${this.props.root}stores/admin/addCover`, {
      method: "post",
      body: formdata
    })
      .then(res => res.text())
      .then(text => {
        if (text == "true") {
          this.props.resetState();
        } else {
          this.alertError(alertElem, "error-alert", "An error occurred");
        }
      })
      .catch(err => {
        console.log(err);
      });
  }

  render() {
    return (
      <React.Fragment>
        <small id="alert" />
        <div className="s-coverPhoto">
          <img
            id="s-coverPhoto-img"
            src={`${this.props.uroot}public/images/storeCoverPhoto/${
              this.props.storeCoverPhoto
            }`}
          />
          <div className="s-addCoverBtn-js">
            <label htmlFor="coverPhoto-input">text</label>
            <input
              id="coverPhoto-input"
              type="file"
              onChange={this.onChange}
              name="coverPhoto"
            />
            {this.props.storeCoverPhoto == "" ? (
              <button className="btn">Add Cover Photo</button>
            ) : (
              <button className="btn">Change Cover Photo</button>
            )}
          </div>
        </div>
      </React.Fragment>
    );
  }
}

export default CoverPhoto;
