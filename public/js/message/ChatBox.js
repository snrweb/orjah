import React, { Component } from "react";
import TimeDiff from "../helpers/TimeDiff";

class ChatBox extends Component {
  constructor(props) {
    super(props);
    this.state = {
      userID: "",
      messageLists: [],
      receiverId: "",
      message: ""
    };

    this.loadChat = this.loadChat.bind(this);
    this.onSubmit = this.onSubmit.bind(this);
    this.onChange = this.onChange.bind(this);

    this.chatThreadRef = React.createRef();
  }

  componentDidMount() {
    let uniqueId = "";
    if (this.props.match != undefined) {
      uniqueId = this.props.match.params.uniqueId;
    } else {
      uniqueId = this.props.uniqueId;
    }

    if (this.props.isLoggedInStore) {
      fetch(`${this.props.root}message/read/${uniqueId}`)
        .then(res => res.json())
        .then(res => {
          this.setState({ messageLists: res.messageLists, userID: res.userID });
          res.messageLists.forEach(msg => {
            if (msg.sender_type == "buyer") {
              this.setState({ receiverId: msg.sender_id });
              return;
            }
          });
        })
        .catch(err => {
          console.log(err);
        });
    }

    if (this.props.isLoggedInBuyer) {
      fetch(`${this.props.root}message/read/${uniqueId}`)
        .then(res => res.json())
        .then(res => {
          this.setState({ messageLists: res.messageLists, userID: res.userID });
          res.messageLists.forEach(msg => {
            if (msg.sender_type == "store") {
              this.setState({ receiverId: msg.sender_id });
              return;
            }
          });
        })
        .catch(err => {
          console.log(err);
        });
    }
  }

  componentDidUpdate(prevProps, prevState, snapshot) {
    if (snapshot !== null) {
      const chatThreadRef = this.chatThreadRef.current;
      chatThreadRef.scrollTop = chatThreadRef.scrollHeight - snapshot - 20;
    }
  }

  getSnapshotBeforeUpdate(prevProps, prevState) {
    if (
      (this.props.uniqueId != undefined &&
        this.props.uniqueId !== prevProps.uniqueId) ||
      (this.props.match != undefined &&
        this.props.match.params.uniqueId !== prevProps.match.params.uniqueId)
    ) {
      const uniqueId = this.props.uniqueId;

      if (this.props.isLoggedInStore) {
        fetch(`${this.props.root}message/read/${uniqueId}`)
          .then(res => res.json())
          .then(res => {
            this.setState({
              messageLists: res.messageLists,
              userID: res.userID
            });
            res.messageLists.forEach(msg => {
              if (msg.sender_type == "buyer") {
                this.setState({ receiverId: msg.sender_id });
                return;
              }
            });
          })
          .catch(err => {
            console.log(err);
          });
      }

      if (this.props.isLoggedInBuyer) {
        fetch(`${this.props.root}message/read/${uniqueId}`)
          .then(res => res.json())
          .then(res => {
            this.setState({
              messageLists: res.messageLists,
              userID: res.userID
            });
            res.messageLists.forEach(msg => {
              if (msg.sender_type == "store") {
                this.setState({ receiverId: msg.sender_id });
                return;
              }
            });
          })
          .catch(err => {
            console.log(err);
          });
      }
    }

    if (this.state.messageLists.length > prevState.messageLists.length) {
      const chatThreadRef = this.chatThreadRef.current;
      return chatThreadRef.scrollHeight - chatThreadRef.scrollTop;
    }
    return null;
  }

  loadChat() {
    if (this.props.isLoggedInStore) {
      return this.state.messageLists.map(msg =>
        msg.sender_id == this.state.userID && msg.sender_type == "store" ? (
          <React.Fragment key={msg.message_id}>
            <div className="pull-right ChatOnlineUser">
              <span>You</span>
              <p>{msg.message}</p>
              <span
                className="pull-right"
                style={{ fontWeight: 500, fontSize: 13 + "px" }}
              >
                <TimeDiff date={msg.created_at} />
              </span>
            </div>
            <div className="clear-float" />
          </React.Fragment>
        ) : (
          <React.Fragment key={msg.message_id}>
            <div className="pull-left ChatOfflineUser">
              <span>{msg.sender_name}</span>
              <p>{msg.message}</p>
              <span
                className="pull-right"
                style={{ fontWeight: 500, fontSize: 13 + "px" }}
              >
                <TimeDiff date={msg.created_at} />
              </span>
            </div>
            <div className="clear-float" />
          </React.Fragment>
        )
      );
    }

    if (this.props.isLoggedInBuyer) {
      return this.state.messageLists.map(msg =>
        msg.sender_id == this.state.userID && msg.sender_type == "buyer" ? (
          <React.Fragment key={msg.message_id}>
            <div className="pull-right ChatOnlineUser">
              <span>You</span>
              <p>{msg.message}</p>
              <span
                className="pull-right"
                style={{ fontWeight: 500, fontSize: 13 + "px" }}
              >
                <TimeDiff date={msg.created_at} />
              </span>
            </div>
            <div className="clear-float" />
          </React.Fragment>
        ) : (
          <React.Fragment key={msg.message_id}>
            <div className="pull-left ChatOfflineUser">
              <span>{msg.sender_name}</span>
              <p>{msg.message}</p>
              <span
                className="pull-right"
                style={{ fontWeight: 500, fontSize: 13 + "px" }}
              >
                <TimeDiff date={msg.created_at} />
              </span>
            </div>
            <div className="clear-float" />
          </React.Fragment>
        )
      );
    }
  }

  onChange(e) {
    this.setState({ [e.target.name]: e.target.value });
  }

  onSubmit(e) {
    e.preventDefault();
    if (this.state.message.trim() == "") return;
    let formdata = new FormData();
    formdata.append("message", this.state.message);

    fetch(`${this.props.root}message/send/${this.state.receiverId}`, {
      method: "post",
      body: formdata
    })
      .then(res => res.json())
      .then(res => {
        this.setState({ messageLists: res.messageLists });
        document.querySelector("#message").value = "";
      })
      .catch(err => {
        console.log(err);
      });
  }

  render() {
    return (
      <div className="ChatBox pull-left">
        <div className="ChatBody" ref={this.chatThreadRef}>
          {this.loadChat()}
        </div>

        <form className="ChatAction" method="post" onSubmit={this.onSubmit}>
          <textarea
            placeholder="Type your message here..."
            rows="3"
            name="message"
            id="message"
            onChange={this.onChange}
          />
          <button className="btn" type="submit">
            Send
          </button>
        </form>
        <div className="clear-float" />
      </div>
    );
  }
}

export default ChatBox;
