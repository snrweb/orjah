import React, { Component } from "react";
import { Link } from "react-router-dom";

import Facebook from "./../../images/svg/facebook.svg";
import Twitter from "./../../images/svg/twitter.svg";
import Instagram from "./../../images/svg/instagram.svg";

class SecondNavBar extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (
      <section className="s-secondNavBar">
        <div className="s-secondNavBar-menu">
          {this.props.SecondNavBarState && (
            <div>
              {this.props.categoryOne != undefined ? (
                <a href={`#${this.props.categoryOne.replace(/ /g, "-")}`}>
                  <span>{this.props.categoryOne}</span>
                </a>
              ) : (
                ""
              )}

              {this.props.categoryTwo != undefined ? (
                <a href={`#${this.props.categoryTwo.replace(/ /g, "-")}`}>
                  <span>{this.props.categoryTwo}</span>
                </a>
              ) : (
                ""
              )}

              {this.props.categoryThree != undefined ? (
                <a href={`#${this.props.categoryThree.replace(/ /g, "-")}`}>
                  <span>{this.props.categoryThree}</span>
                </a>
              ) : (
                ""
              )}

              {this.props.categoryFour != undefined ? (
                <a href={`#${this.props.categoryFour.replace(/ /g, "-")}`}>
                  <span>{this.props.categoryFour}</span>
                </a>
              ) : (
                ""
              )}
            </div>
          )}
          <div style={{ marginLeft: 2 + "%" }}>
            <span>
              <Link to={`/${this.props.storeName.replace(/ /g, "-")}/about`}>
                About us
              </Link>
            </span>
            <span>
              <Link to={`/${this.props.storeName.replace(/ /g, "-")}/contact`}>
                Contact
              </Link>
            </span>
          </div>

          <div className="s-columnTwo-SocialContactBtn">
            <span>Follow us on:</span>

            {this.props.facebook != "" && (
              <a href={this.props.facebook}>
                <button className="s-columnTwo-FacebookPage btn">
                  <Facebook />
                </button>
              </a>
            )}

            {this.props.twitter != "" && (
              <a href={this.props.twitter}>
                <button className="s-columnTwo-TwitterPage btn">
                  <Twitter />
                </button>
              </a>
            )}

            {this.props.instagram != "" && (
              <a href={this.props.instagram}>
                <button className="s-columnTwo-InstagramPage btn">
                  <Instagram />
                </button>
              </a>
            )}
          </div>
        </div>
      </section>
    );
  }
}

export default SecondNavBar;
