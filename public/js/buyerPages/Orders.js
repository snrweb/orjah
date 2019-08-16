import React, { Component } from "react";
import TimeDiff from "../helpers/TimeDiff";

class Orders extends Component {
  constructor(props) {
    super(props);
    this.state = {
      orders: []
    };

    this.deleteOrder = this.deleteOrder.bind(this);
  }

  componentDidMount() {
    fetch(`${this.props.root}order`)
      .then(res => res.json())
      .then(data => {
        this.setState({ orders: data.orders });
      })
      .catch(err => {
        console.log(err);
      });

    document.body.style.background = "#f9f9f9";
  }

  deleteOrder(e) {
    let dataId = e.target.getAttribute("data-id");
    if (confirm("Delete this order?")) {
      fetch(`${this.props.root}order/cancel/${dataId}`)
        .then(res => res.text())
        .then(text => {
          if (text == "true") {
            document.querySelector(`#s-orders${dataId}`).style.display = "none";
            let navOrderNotifElem = document.querySelector(
              "#navBarOrders-span"
            );
            let navOrderNotifValue = parseInt(navOrderNotifElem.innerHTML) - 1;
            navOrderNotifElem.innerHTML = navOrderNotifValue;
          }
        })
        .catch(err => {
          console.log(err);
        });
    }
  }

  render() {
    return (
      <div className="s-ordersWrapper-buyer">
        {this.state.orders.length < 1 && (
          <p
            style={{
              textAlign: "center",
              border: 1 + "px dashed #ccc",
              fontSize: 25 + "px",
              margin: 80 + "px auto " + 100 + "px",
              padding: 40 + "px" + 10 + "px"
            }}
          >
            Order list Empty
          </p>
        )}

        <h3>List Of Orders</h3>

        {this.state.orders.map(o => (
          <div className="s-orders" id={`s-orders${o.order_id}`}>
            <div className="s-order">
              <p>
                Order ID: <small>{o.order_id} </small>
                <small> | </small>
                <small>
                  {" "}
                  <TimeDiff date={o.created_at} />{" "}
                </small>
              </p>
              <div className="s-orderSection">
                <div>Product Name</div>
                <div>{o.product_name}</div>
                <div className="clear-float" />
              </div>

              <div className="s-orderSection">
                <div>Category</div>
                <div>
                  {o.product_cat}, {o.product_sub_cat}
                </div>
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
            <div className="clear-float" />
            <button
              data-id={o.order_id}
              className="btn s-deleteOrder"
              onClick={this.deleteOrder}
            >
              Cancel Order
            </button>
          </div>
        ))}
      </div>
    );
  }
}

export default Orders;
