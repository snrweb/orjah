import React, { Component } from "react";

class UploadLogo extends Component {
  constructor(props) {
    super(props);

    this.state = {
      hasError: false,
      image: {}
    };

    this.onChange = this.onChange.bind(this);
    this.onSubmit = this.onSubmit.bind(this);
    this.alertError = this.alertError.bind(this);
  }

  alertError(elem, className, errorMessage) {
    elem.classList.add(className);
    elem.innerHTML = errorMessage;
  }

  onChange(e) {
    this.setState({ hasError: false });

    let image = e.target.files[0];
    this.setState({ image: image });

    let alertElem = document.querySelector("#alert");

    if (alertElem.classList.contains("error-alert")) {
      alertElem.classList.remove("error-alert");
    }

    if (alertElem.classList.contains("success-alert")) {
      alertElem.classList.remove("success-alert");
    }
    alertElem.innerHTML = "";

    if (image.name == "") {
      this.alertError(alertElem, "error-alert", "Please select an image");
      this.setState({ hasError: true });
      return;
    }

    let extension = image.type
      .split("/")
      .pop()
      .toLowerCase();
    if (["jpg", "png", "jpeg"].indexOf(extension) == -1) {
      this.alertError(
        alertElem,
        "error-alert",
        "The selected file is not an image"
      );
      this.setState({ hasError: true });
      return;
    }

    if (image.size > 2500000) {
      this.alertError(
        alertElem,
        "error-alert",
        "Image should not be more than 2mb"
      );
      this.setState({ hasError: true });
      return;
    }

    let preview = document.querySelector(".s-logoPreviewWrapper img");
    preview.style.display = "block";
    preview.src = URL.createObjectURL(image);
  }

  onSubmit(e) {
    e.preventDefault();
    let alertElem = document.querySelector("#alert");

    let image = this.state.image;
    if (this.state.hasError == true) return;

    let formdata = new FormData();
    formdata.append("store_logo", image);
    fetch(`${this.props.root}stores/admin/editLogo`, {
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
      <form
        className="s-form"
        method="post"
        encType="multipart/form-data"
        onSubmit={this.onSubmit}
      >
        <p className="s-formTitle s-formTitle-alt">Upload Store Logo</p>
        <small id="alert" />
        <div className="input-wrapper">
          <div className="s-logoInputWrapper-js">
            <label htmlFor="s-logoInput-js" />
            <input
              type="file"
              className="input"
              id="s-logoInput-js"
              name="store_logo"
              onChange={this.onChange}
            />
            <button type="button" className="btn">
              Select Logo From Document
            </button>
          </div>

          <div className="s-logoPreviewWrapper">
            <img src="#" />
          </div>

          <div className="clear-float" />

          <small className="errorDisplay">
            <i>
              We advice you use a landscape image dimension (preferrably less
              than 900 x 600)
            </i>
          </small>
        </div>
        <div className="input-wrapper">
          <input
            type="submit"
            value="Upload Logo"
            className="btn s-submitbtn s-submitbtn-alt"
          />
        </div>
      </form>
    );
  }
}

export default UploadLogo;
