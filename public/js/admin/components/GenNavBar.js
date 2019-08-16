import React, { Component } from "react";
import { Link } from "react-router-dom";
import Logo from "../../../images/logo/logo2.jpg";

class GenNavBar extends Component {
  constructor(props) {
    super(props);

    this.logout = this.logout.bind(this);
  }

  logout() {
    fetch(`${this.props.root}logout`)
      .then(res => res.text())
      .then(text => {
        if (text == "true") {
          window.open(`${this.props.uroot}login/adminLogin`);
        }
      })
      .catch(err => {
        console.log(err);
      });
  }

  render() {
    const logoImg = {
      backgroundImage: `url(${this.props.uroot}public/js/dist${Logo})`
    };
    return (
      <div>
        <nav className="navBar">
          <Link to="/">
            <div className="navBarLogo" style={logoImg} />
          </Link>

          <Link to="/logout">
            <button className="btn a-logout-btn" onClick={this.logout}>
              Logout
            </button>
          </Link>

          <Link to="/editAbout">
            <button className="btn a-editAbout-btn">Edit About</button>
          </Link>

          <Link to="/editTerms">
            <button className="btn a-editTerms-btn">Edit Terms</button>
          </Link>
        </nav>
      </div>
    );
  }
}

export default GenNavBar;
