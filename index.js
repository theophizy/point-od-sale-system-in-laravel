import express from "express"
import { object, string, number, array } from "yup"

const app = express()
app.use(express.json())

app.post("/split-payments/compute", async (req, res) => {
  const rules = object({
    ID: number().required(),
    Amount: number().required(),
    Currency: string().required(),
    CustomerEmail: string().required().email(),
    SplitInfo: array()
      .of(
        object({
          SplitType: string().required().oneOf(["FLAT", "RATIO", "PERCENTAGE"]),
          SplitValue: number()
            .required()
            .min(0)
            .max(req.body.Amount)
            .when("SplitType", {
              is: "PERCENTAGE",
              then: number().required().min(0).max(100),
            }),
          SplitEntityId: string().required(),
        })
      )
      .min(1)
      .max(20),
  })
  rules
    .validate(req.body, { stripUnknown: true })
    .then((value) => {
      const split = {
        FLAT: [],
        RATIO: [],
        PERCENTAGE: [],
      }
      value.SplitInfo.forEach((item) => {
        split[item.SplitType].push(item)
       
      })
     // console.log(split)
      let remaining = value.Amount
      const SplitBreakdown = []
      split.FLAT.forEach((item) => {
        remaining -= item.SplitValue
       // remaining =remaining - item.SplitValue
        SplitBreakdown.push({
          SplitEntityId: item.SplitEntityId,
          Amount: item.SplitValue,
        })
      })
      if (remaining < 0) {
        res.status(400).json({
          message: "The sum of all split Amount cannot be greater than the transaction",
        })
      } else {
        split.PERCENTAGE.forEach((item) => {
          const amount = (remaining * item.SplitValue) / 100
          remaining -= amount
        // remaining = remaining - amount
          SplitBreakdown.push({
            SplitEntityId: item.SplitEntityId,
            Amount: amount,
          })
        })
        const ratioSum = split.RATIO.reduce(
          (partialSum, a) => partialSum + a.SplitValue,
          0
        )
        const remainingBeforeRatio = remaining
        split.RATIO.forEach((item) => {
       //  const amount = (remainingBeforeRatio / ratioSum) * item.SplitValue
       const amount = ( item.SplitValue / ratioSum) * remainingBeforeRatio
         remaining -= amount
          //remaining = remaining - amount
          SplitBreakdown.push({
            SplitEntityId: item.SplitEntityId,
            Amount: amount,
          })
        })
        res.json({
          ID: value.ID,
          Balance: remaining,
          SplitBreakdown,
        })
      }
    })
    .catch((error) => {
      res.status(400).json({ message: error.message })
    })
})

app.all("/", async (req, res) => {
  res.json({ message: "Home" })
})

app.listen(3000, () => {
  console.log("Server running on http://localhost:3000")
})
