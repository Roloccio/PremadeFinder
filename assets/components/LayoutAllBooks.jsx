import React, { useEffect, useState } from 'react'
import Header from './Header';
import Footer from './Footer';
import { Outlet } from 'react-router-dom';


const LayoutAllBooks = ({userGlobal, setUserGlobal}) => {

  return (
    <>
      <Header userGlobal={userGlobal} setUserGlobal={setUserGlobal} />
      <Outlet />
      <Footer />
    </>
  )
}

export default LayoutAllBooks