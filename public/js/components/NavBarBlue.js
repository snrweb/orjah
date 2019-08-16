import React, { Component } from "react";
import Logo from "./../../images/logo/logo2.jpg";
import SearchSvg from "./../../images/svg/search.svg";
import MenuSvg from "./../../images/svg/menu.svg";
import ShopSvg from "./../../images/svg/shop.svg";
import ListSvg from "./../../images/svg/list.svg";
import MessageSvg from "./../../images/svg/message.svg";
import CartSvg from "./../../images/svg/cart.svg";

class NavBarBlue extends Component {
  constructor(props) {
    super(props);

    this.state = {
      totalProductInBasket: 3,
      totalOrders: 2,
      msgCount: 1,
      loggedInBuyer: true,
      newOrders: 0,
      loggedInStore: false
    };
  }

  render() {
    const logoImg = {
      backgroundImage: `url(${Logo})`
    };
    return (
      <nav className="navBar">
        <a href="/">
          <div className="navBarLogo" style={logoImg} />
        </a>

        <div className="navBarSearch">
          <input
            type="text"
            placeholder="Search categories, products, stores..."
          />
        </div>

        <div className="navBarSearch-icon">
          <SearchSvg />
        </div>

        {this.state.loggedInStore ? (
          <div className="navBarMenu-icon">
            <MenuSvg />
          </div>
        ) : (
          ""
        )}

        {this.state.loggedInStore ? (
          <React.Fragment>
            <a href="/stores/admin">
              <div className="navBarAdmin">
                <ShopSvg />
              </div>
            </a>
            <a href="/stores/admin/orders">
              <div className="navBarOrders">
                <ListSvg />
                <span> {this.state.newOrders} </span>
              </div>
            </a>
          </React.Fragment>
        ) : (
          ""
        )}

        {this.state.loggedInStore || this.state.loggedInBuyer ? (
          <a href="/message">
            <div className="navBarMsg">
              <MessageSvg />
              <span>{this.state.msgCount} </span>
            </div>
          </a>
        ) : (
          ""
        )}

        {this.state.loggedInBuyer ? (
          <React.Fragment>
            <a href="/order">
              <div className="navBarOrders">
                <ListSvg />
                <span>{this.state.totalOrders}</span>
              </div>
            </a>

            {this.state.totalProductInBasket != "" ? (
              <a href="/basket">
                <div className="navBarBasket">
                  <CartSvg />
                  <span> {this.state.totalProductInBasket} </span>
                </div>
              </a>
            ) : (
              ""
            )}
          </React.Fragment>
        ) : (
          ""
        )}

        {this.state.loggedInStore && this.state.loggedInBuyer ? (
          <a href="/register">
            <div className="navBarRegister">
              <button className="btn">Sign up</button>
            </div>
          </a>
        ) : (
          ""
        )}

        {this.state.loggedInStore && this.state.loggedInBuyer ? (
          <a href="/login">
            <div className="navBarLogin">
              <button className="btn">Login</button>
            </div>
          </a>
        ) : (
          ""
        )}
      </nav>
    );
  }
}

export default NavBarBlue;
