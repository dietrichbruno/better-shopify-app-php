import React, { useState } from "react";
import {
  Card,
  TextContainer
} from "@shopify/polaris";
import { Toast } from "@shopify/app-bridge-react";
import { useAppQuery, useAuthenticatedFetch } from "../hooks";

export function ProductsCard() {
  const emptyToastProps = { content: null };
  const [isLoading, setIsLoading] = useState(false);
  const [toastProps, setToastProps] = useState(emptyToastProps);
  const [productList, setProductList] = useState([]);
  const [abandonList, setAbandonList] = useState([]);
  const fetch = useAuthenticatedFetch();

  const toastMarkup = toastProps.content  && (
    <Toast {...toastProps} onDismiss={() => setToastProps(emptyToastProps)} />
  );

  const products = async () => {
    setIsLoading(true);
    await fetch("/api/products")
      .then( response => response.json())
      .then(async (data) => {
        const p = await data.products
        setProductList(p)
        setIsLoading(false);
      }).catch((err) => {
        setIsLoading(false);
      })
  };

  const checkouts = async () => {
    setIsLoading(true);
    await fetch("/api/checkouts")
      .then( response => response.json())
      .then(async (data) => {
        const p = await data

        console.log(p)
        setAbandonList(p)
        setIsLoading(false);
      }).catch((err) => {
        setIsLoading(false);
      })
  };

  const subscribe = async () => {
    setIsLoading(true);

    await fetch("/api/subscribe", {method: 'POST'})
      .then( response => response.json())
      .then(async (data) => {
        const p = await data

        console.log(p)
        setIsLoading(false);
      }).catch((err) => {
        setIsLoading(false);
      })
  };

  const listSubscribe = async () => {
    setIsLoading(true);

    await fetch("/api/list-webhooks")
      .then( response => response.json())
      .then(async (data) => {
        const p = await data

        console.log(p)
        setIsLoading(false);
      }).catch((err) => {
        setIsLoading(false);
      })
  };

  return (
    <>
      {toastMarkup}
      <Card
        title="Produtos"
        sectioned
        primaryFooterAction={{
          content: "Listar Produtos",
          onAction: products,
          loading: isLoading,
        }}
      >
        <TextContainer spacing="loose">
          {
          Object.values(productList).map((product) => (
            <p key={product.id}> {product.id} - {product.title }</p>
          ))}
        </TextContainer>
      </Card>

      <Card
        title="Abandonos"
        sectioned
        primaryFooterAction={{
          content: "Listar Abandonos",
          onAction: checkouts,
          loading: isLoading,
        }}
      >
        <TextContainer spacing="loose">
          {/* {
          Object.values(abandonList).map((product) => (
            <p key={product.id}> {product.id} - {product.title }</p>
          ))} */}
        </TextContainer>
      </Card>

      <Card
        title="Webhooks"
        sectioned
        primaryFooterAction={{
          content: "Assinar webhooks",
          onAction: subscribe,
          loading: isLoading,
        }}
      >
        <TextContainer spacing="loose">
          {/* {
          Object.values(abandonList).map((product) => (
            <p key={product.id}> {product.id} - {product.title }</p>
          ))} */}
        </TextContainer>
      </Card>

      <Card
        title="Listar Webhooks"
        sectioned
        primaryFooterAction={{
          content: "Listar webhooks",
          onAction: listSubscribe,
          loading: isLoading,
        }}
      >
        <TextContainer spacing="loose">
          {/* {
          Object.values(abandonList).map((product) => (
            <p key={product.id}> {product.id} - {product.title }</p>
          ))} */}
        </TextContainer>
      </Card>
      <br />
      <br />
    </>
  );
}
