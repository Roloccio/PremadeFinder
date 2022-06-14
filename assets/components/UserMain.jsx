import React, { useState } from 'react'
import HomepageImage from './HomePageImage';
import Libros from './Libros';
import Login from './Login';

const UserMain = ({userGlobal,setUserGlobal}) => {
  
  return (
    <section className="mainIndex">
      {userGlobal ? null : <Login setUserGlobal={setUserGlobal}/>}
      
    </section>
  )
}

export default UserMain