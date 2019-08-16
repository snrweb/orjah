import React, { Component } from "react";

class About extends Component {
  constructor(props) {
    super(props);

    this.state = {
      about: ""
    };

    this.updateAbout = this.updateAbout.bind(this);
    this.submitAbout = this.submitAbout.bind(this);
  }

  updateAbout(e) {
    let about = e.target.value;
    this.setState({ about: about });
  }

  submitAbout(e) {
    e.preventDefault();
    let formdata = new FormData();
    formdata.append("about", this.state.about);
    fetch(`${this.props.root}/editAbout`, {
      method: "POST",
      body: formdata
    })
      .then(res => res.text())
      .then(text => console.log(text))
      .catch(err => {
        console.log(err);
      });
  }

  render() {
    return (
      <form className="form" method="post">
        <p className="formTitle">About Orjah</p>
        <div className="input-wrapper">
          <textarea
            type="url"
            className="textarea"
            placeholder="About Orjah"
            name="about"
            rows="10"
            value={this.state.about}
            onChange={this.updateAbout}
          />
        </div>
        <div className="input-wrapper">
          <input
            type="submit"
            value="Submit"
            className="btn submitbtn"
            onClick={this.submitAbout}
          />
        </div>
      </form>
    );
  }
}

export default About;
