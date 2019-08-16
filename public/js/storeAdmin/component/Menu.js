import React, { Component } from "react";
import { Link } from "react-router-dom";

import RatingSVG from "../../helpers/RatingSVG";

class Menu extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const month = [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec"
    ];
    return (
      <section className="container">
        <div className="mobile-menu" style={{ width: 100 + "%" }}>
          <div className="s-storeLogo">
            <img
              id="s-storeLogo-img"
              src={`${this.props.uroot}public/images/storeLogos/${
                this.props.storeLogo
              }`}
            />

            <a href={`/${this.props.storeName.replace(/ /g, "-")}`}>
              {this.props.storeName}
            </a>
          </div>

          <div className="s-columnOne-sections">
            <p className="s-columnOne-title">Edit Store</p>
            <div className="s-columnOne-subSections">
              <Link to="/stores/admin/editLogo">Upload Logo</Link>
              <Link to="/stores/admin/editAbout">About Store</Link>
              <Link to="/stores/admin/editSocial">Add Social Page</Link>
              <Link to="/stores/admin/editContact">Edit Contact</Link>
              <Link to="/stores/admin/storeCategories">Add Categories</Link>
              <Link to="/stores/admin/editDelivery">
                Update Delivery and Return Policy
              </Link>
            </div>
          </div>

          <div className="s-columnOne-sections">
            <p className="s-storeVisit-title">
              Store visit for the last 7 days
            </p>
            <div className="s-storeVisit">
              <div className="s-storeVisit-barWrapper">
                {this.props.storeVisit.map((v, k) => (
                  <div
                    key={k}
                    className="s-storeVisit-bar"
                    style={{
                      height:
                        (v.visit_count / this.props.totalVisit) * 145 +
                        "px" +
                        " ",
                      left: k * 14 + "%"
                    }}
                  >
                    <span key={k}>{v.visit_count}</span>
                  </div>
                ))}
              </div>

              <div className="s-storeVisit-axis" />

              {this.props.storeVisit.map((v, k) => (
                <div className="s-storeVisit-day" key={k}>
                  {v.visit_day} <span>{v.visited_at.split("-")[2]}</span>
                  {month[parseInt(v.visited_at.split("-")[1]) - 1]}
                </div>
              ))}
            </div>

            <div className="s-columnOne-subSections">
              <span
                title={`${
                  this.props.storeBaskets
                } of your products is currently in buyers' basket`}
              >
                Total carted products : <b>{this.props.storeBaskets}</b>
              </span>
              <span title="Your store is bookmarked by { this.state.store_bookmarks } buyers">
                Bookmarks : <b>{this.props.storeBookmarks}</b>
              </span>
              <span>
                Store rating :{" "}
                <b>
                  <RatingSVG rate={this.props.storeRating} />
                </b>
              </span>
            </div>
          </div>

          <a href="/logout" className="s-logoutBtn">
            Logout
          </a>
        </div>
      </section>
    );
  }
}

export default Menu;
