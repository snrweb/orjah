import React, { Component } from "react";
import TimeDiff from "../helpers/TimeDiff";

class Orders extends Component {
  constructor(props) {
    super(props);

    this.state = {
      orders: [],
      buyerId: "",
      message: "",
      orderId: ""
    };

    this.onChange = this.onChange.bind(this);
    this.onFocus = this.onFocus.bind(this);
    this.alertMsg = this.alertMsg.bind(this);
    this.onSubmit = this.onSubmit.bind(this);
    this.onDelete = this.onDelete.bind(this);
  }

  alertMsg(elem, className, errorMessage) {
    elem.classList.add(className);
    elem.innerHTML = errorMessage;
  }

  componentDidMount() {
    fetch(`${this.props.root}stores/admin/orders`)
      .then(res => res.json())
      .then(json => {
        this.setState({
          orders: json.orders
        });
      })
      .catch(err => {
        console.log(err);
      });
  }

  onFocus(e) {
    const buyerId = e.target.getAttribute("data-buyerid");
    const orderId = e.target.getAttribute("data-oid");
    this.setState({ buyerId, orderId });

    let alertElem = document.querySelector(`#alert${orderId}`);

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
    let alertElem = document.querySelector(`#alert${this.state.orderId}`);

    let formdata = new FormData();
    formdata.append("buyer_id", this.state.buyerId);
    formdata.append("store_name", this.props.storeName);
    formdata.append("message", this.state.message);

    fetch(`${this.props.root}stores/admin/orders`, {
      method: "post",
      body: formdata
    })
      .then(res => res.text())
      .then(text => {
        console.log(text);
        if (text == "true") {
          this.alertMsg(alertElem, "success-alert", "Message sent");
        } else {
          this.alertMsg(alertElem, "error-alert", "An error occurred");
        }
      })
      .catch(err => {
        console.log(err);
      });
  }

  onDelete(e) {
    e.preventDefault();
    if (confirm("Delete this order?")) {
      let orderId = e.target.getAttribute("data-oid");
      let alertElem = document.querySelector(`#alert`);

      fetch(`${this.props.root}stores/admin/deleteOrder/${orderId}`)
        .then(res => res.text())
        .then(text => {
          console.log(text);
          if (text == "true") {
            this.alertMsg(alertElem, "success-alert", "Order Deleted");
            document.querySelector(`#s-orders${orderId}`).style.display =
              "none";
          } else {
            this.alertMsg(alertElem, "error-alert", "An error occurred");
          }
        })
        .catch(err => {
          console.log(err);
        });
    }
  }

  render() {
    return (
      <React.Fragment>
        <div className="s-ordersWrapper">
          <h3>List Of Orders</h3>
          <small id="alert" />

          {this.state.orders.map(o => (
            <div
              className="s-orders"
              id={`s-orders${o.order_id}`}
              key={o.order_id}
            >
              <small id={`alert${o.order_id}`} />
              <div className="pull-left s-order">
                <p>
                  Order ID: <small>{o.order_id} </small>
                  <small> | </small>
                  <small>
                    <TimeDiff date={o.created_at} />
                  </small>
                </p>
                <div className="s-orderSection">
                  <div>Product Name</div>
                  <div>{o.product_name}</div>
                  <div className="clear-float" />
                </div>

                <div className="s-orderSection">
                  <div>Category</div>
                  <div>{o.product_cat}</div>
                  <div className="clear-float" />
                </div>

                <div className="s-orderSection">
                  <div>Quantity</div>
                  <div>{o.quantity}</div>
                  <div className="clear-float" />
                </div>

                <div className="s-orderSection">
                  <div>Buyer Name</div>
                  <div>{o.buyer_name}</div>
                  <div className="clear-float" />
                </div>
              </div>
              <form
                className="pull-left"
                method="post"
                onSubmit={this.onSubmit}
              >
                <textarea
                  rows="5"
                  placeholder={`Reply ${o.buyer_name}...`}
                  name="message"
                  data-buyerid={o.buyer_id}
                  data-oid={o.order_id}
                  onChange={this.onChange}
                  onFocus={this.onFocus}
                />
                <button
                  type="submit"
                  className="btn"
                  style={{ marginTop: 5 + "px" }}
                  onSubmit={this.onSubmit}
                >
                  Send Reply
                </button>
              </form>

              <div className="clear-float" />
              <button
                className="btn s-deleteOrder"
                data-oid={o.order_id}
                onClick={this.onDelete}
              >
                Delete
              </button>
            </div>
          ))}
        </div>
      </React.Fragment>
    );
  }
}

export default Orders;
