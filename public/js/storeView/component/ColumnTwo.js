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

  render() {
    return (
      <React.Fragment>
        <CoverPhoto
          storeCoverPhoto={this.props.storeCoverPhoto}
          root={this.props.root}
          uroot={this.props.uroot}
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
                {" "}
                {storeMenu}
              </h4>

              {this.props.products.map(
                pr =>
                  pr.store_menu == storeMenu && (
                    <div
                      className="s-product"
                      id={`s-product${pr.product_id}`}
                      key={pr.product_id}
                    >
                      <a
                        href={`${this.props.uroot}/details/${
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
                    </div>
                  )
              )}
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
