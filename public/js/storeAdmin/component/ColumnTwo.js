import React, { Component } from "react";
import { Link } from "react-router-dom";

import TruckOne from "./../../../images/svg/truck-1.svg";
import TruckFour from "./../../../images/svg/truck-4.svg";
import CoverPhoto from "./CoverPhoto";
import RatingSVG from "../../helpers/RatingSVG";

class ColumnTwo extends Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {
    this.props.changeNavBarState();
  }

  componentWillUnmount() {
    this.props.changeNavBarState();
  }

  render() {
    return (
      <React.Fragment>
        <CoverPhoto
          storeCoverPhoto={this.props.storeCoverPhoto}
          root={this.props.root}
          uroot={this.props.uroot}
          resetState={this.props.resetState}
        />

        <div className="s-deliveryPolicy">
          <div>
            <TruckOne style={{ fill: "#544c74" }} />
            <Link to={`/${this.props.storeName.replace(/ /g, "-")}/delivery`}>
              Goods Delivery
            </Link>
          </div>
          <div>
            <TruckFour style={{ fill: "#544c74" }} />
            <Link to={`/${this.props.storeName.replace(/ /g, "-")}/return`}>
              Our Return Policy
            </Link>
          </div>
        </div>

        <div className="s-productSection">
          {this.props.storeMenus.map(storeMenu => (
            <div className="s-productCategory" key={storeMenu}>
              <h4
                className="s-productCategoryTitle"
                id={storeMenu.replace(/ /g, "-")}
              >
                {storeMenu}
              </h4>

              {this.props.products.map(
                pr =>
                  pr.store_menu == storeMenu && (
                    <div
                      className="s-product"
                      style={{ marginBottom: 50 + "px" }}
                      id={`s-product${pr.product_id}`}
                      key={pr.product_id}
                    >
                      <a
                        href={`/orjah/details/${
                          pr.product_id
                        }/${pr.product_name.replace(/ /g, "-")}`}
                      >
                        <div className="s-productImage">
                          <img
                            src={`${this.props.uroot}public/images/products/${
                              pr.product_image_one
                            }`}
                          />
                        </div>
                        <span className="s-productName">{pr.product_name}</span>
                        <span>
                          <RatingSVG rate={pr.product_rating} />
                        </span>
                        <span className="s-productPrice">
                          NGN {pr.product_price}
                        </span>
                      </a>
                      <Link to={`/stores/admin/modifyProduct/${pr.product_id}`}>
                        <button className="s-productEdit btn">Edit</button>
                      </Link>
                      <button
                        className="s-productDelete btn"
                        data-pid={pr.product_id}
                        onClick={this.props.deleteProduct}
                      >
                        Delete
                      </button>
                    </div>
                  )
              )}
              <Link to="/stores/admin/addProduct">
                <div className="s-product s-addProduct">
                  <h1>+</h1>
                </div>
              </Link>
              <div className="clear-float" />
            </div>
          ))}
          <div className="clear-float" />
        </div>
      </React.Fragment>
    );
  }
}

export default ColumnTwo;
