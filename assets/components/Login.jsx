import React, { useState } from 'react'

const loginUrl = `http://127.0.0.1:8000/api/login`;

const Login = ({setUserGlobal}) => {
  const [inputUsername, setInputUsername] = useState("");
  const [inputPassword, setInputPassword] = useState("");

  const fetchPost = async (url, objectToUpload) => {
    //console.log(JSON.stringify(objectToUpload));
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(objectToUpload)
      });
      //console.log(response);
      const data = await response.json();
      console.log(data);
      if ("error" in data) {
        alert("Credenciales inválidas");
        return;
      }
      setUserGlobal(data);

    } catch (error) {
      console.log(error);
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (inputUsername.length === 0 || inputPassword.length === 0) {
      alert("Rellene todos los campos");
      return;
    }
    const data = {
      username: inputUsername,
      password: inputPassword
    }
    //console.log(data);
    fetchPost(loginUrl, data);
  }

  const handleChange = (e) => {
    if (e.target.name === "username") {
      setInputUsername(e.target.value);
    }
    if (e.target.name === "password") {
      setInputPassword(e.target.value);
    }
  }
  return (
    <form method="post" onSubmit={handleSubmit}>
        <h3 className="mb-3 mb-md-4 font-weight-normal">Log In</h3>
        
        <div className="form-group">
          <label htmlFor="inputUsername">Nombre </label>
          <input type="text" value={inputUsername} onChange={handleChange} name="username" id="inputUsername" className="form-control" autoComplete="username" required autoFocus />
        </div>

        <div className="form-group">
          <label htmlFor="inputPassword">Contraseña</label>
          <input type="password" value={inputPassword} onChange={handleChange} name="password" id="inputPassword" className="form-control" autoComplete="current-password" required />
        </div>
            
        <input type="hidden" name="_csrf_token" value="{{ csrf_token('authenticate') }}"/>
            
        <button className="btn btn-lg btn-primary" type="submit">
          Entrar
        </button>
        <button className="btn btn-lg btn-primary">
          <a href="/admin/user/new">Registarse</a>
        </button>
    </form>
  )
}

export default Login