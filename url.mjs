
import fetch from "node-fetch"
import * as dotenv from 'dotenv'
import fs from "fs"
import os from "os"
import path from "path"

dotenv.config({path:'.env'})

const data = await fetch("http://localhost:4040/api/tunnels")
  .then((response) => response.json())
  .then(async (data) => {return data});

const url = data.tunnels[0].public_url

if (! url) {
  throw new Error("url ngrok nao encontrada. verifique seu tunel em http://localhost:4040")
}

const envFilePath = path.resolve(".env");
const readEnvVars = () => fs.readFileSync(envFilePath, "utf-8").split(os.EOL);

const setEnvValue = (key, value) => {
  const envVars = readEnvVars();
  const targetLine = envVars.find((line) => line.split("=")[0] === key);
  if (targetLine !== undefined) {
    const targetLineIndex = envVars.indexOf(targetLine);
    envVars.splice(targetLineIndex, 1, `${key}="${value}"`);
  } else {
    envVars.push(`${key}="${value}"`);
  }
  fs.writeFileSync(envFilePath, envVars.join(os.EOL));
};

setEnvValue('HOST', url)

console.log("\x1b[34m",'App URL', "\x1b[0m")
console.log(url)
console.log("\x1b[34m",'Allowed redirection URLs', "\x1b[0m")
console.log(url + '/auth/callback,' + url + '/auth/shopify/callback,' + url + '/api/auth/callback')