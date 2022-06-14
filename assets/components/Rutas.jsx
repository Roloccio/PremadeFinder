import React, { useState, useEffect } from 'react'
import { BrowserRouter, Route, Router, Routes } from 'react-router-dom';
import LayoutAllBooks from './LayoutAllBooks';
import UserMain from './UserMain';
import LibroIndv from './LibroIndv';
import Cookies from './Cookies';

const myStorage = window.localStorage;

const Rutas = () => {

    const [userGlobal, setUserGlobal] = useState(false);

    useEffect(() => {
        if (typeof myStorage.getItem('bibliotecaLoggedUser') === "string") {
            //console.log("storage es string?? - siempre es string");
            //console.log(myStorage.getItem('bibliotecaLoggedUser'));
            setUserGlobal(JSON.parse(myStorage.getItem('bibliotecaLoggedUser')));
        } 
    }, [])

    useEffect(() => {
        //console.log("effect userglobal");
        if (myStorage.getItem('bibliotecaLoggedUser') === null) {
            //console.log("storage es null");
            if (userGlobal) {
                //console.log("entra");
                myStorage.setItem('bibliotecaLoggedUser', JSON.stringify(userGlobal));
            }
        } 
    }, [userGlobal])
    


    return (
        <BrowserRouter>
            <Routes>
                <Route path="/" element={<LayoutAllBooks userGlobal={userGlobal} setUserGlobal={setUserGlobal}/>}>
                    <Route index element={<UserMain  userGlobal={userGlobal} setUserGlobal={setUserGlobal}/>} />
                    {/* 
                    <Route path="libro/:id" element={<LibroIndv userGlobal={userGlobal} />} /> */}
                    <Route path="cookies" element={<Cookies/>} />
                </Route>
            </Routes>
        </BrowserRouter>
    )
}

export default Rutas