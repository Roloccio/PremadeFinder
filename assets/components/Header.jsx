import React from 'react'
import { Link } from 'react-router-dom';

const myStorage2 = window.localStorage;
const logoutUrl =  `http://127.0.0.1:8000/logout`;

const Header = ({ userGlobal, setUserGlobal }) => {

  const logout = async (url) => {
    try {
      await fetch(url);
    } catch (error) {
      console.log(error);
    }
  };

  const handleLogout = (e) => {
    e.preventDefault();
    console.log("logout");
    logout(logoutUrl);
    myStorage2.removeItem('bibliotecaLoggedUser');
    setUserGlobal(false);
  }

  return (
    <header>
      <nav className="navbar w-100">
        <Link to="/"><img id="logo" src="/img/logo.png" alt="logoHLANZ" /></Link>
        <h1>PREMADE FINDER</h1>
        <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span className="navbar-toggler-icon"></span>
        </button>

        <div className="collapse navbar-collapse" id="navbarNav">
          <ul className="navbar-nav">
            <li>
              <a className="nav-link no-link">{userGlobal?.username ? `Bienvenido ${userGlobal?.username}` : `Bienvenido invitado registrese` }</a>
            </li>
            <li className="nav-item">
              {userGlobal?.roles?.find(rol => rol === "ROLE_ADMIN") ? <a className="nav-link" href="/admin">Panel de administración</a> : null}
            </li>
            <li className="nav-item">
              
              {userGlobal?.username ? <a className="nav-link" /* href="/logout" */ onClick={handleLogout}>Cerrar sesión</a> : null }              
            </li>
          </ul>
        </div>
      </nav>
    </header>
  )
}

export default Header