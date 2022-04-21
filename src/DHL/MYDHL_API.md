
```json
{
    "ShipmentRequest": {
        "RequestedShipment": {
            "ShipmentInfo": {
                "DropOffType": "REGULAR_PICKUP",
                "ServiceType": "P",
                "Account": "107037198",
                "Currency": "EUR",
                "UnitOfMeasurement": "SI"
            },
            "ShipTimestamp": "2020-04-30T08:55:20 GMT+00:00",
            "PaymentInfo": "DAP",
            "InternationalDetail": {
                "Commodities": {
                    "NumberOfPieces": 2,
                    "Description": "Customer Reference 1",
                    "CountryOfManufacture": "CN",
                    "Quantity": 1,
                    "UnitPrice": 5,
                    "CustomsValue": 10
                },
                "Content": "NON_DOCUMENTS"
            },
            "Ship": {
                "Shipper": {
                    "Contact": {
                        "PersonName": "LILIan",
                        "CompanyName": "DHLRequest",
                        "PhoneNumber": 2175441239,
                        "EmailAddress": "jb@acme.com"
                    },
                    "Address": {
                        "StreetLines": "VIA LUIGI CANONICA 27 27",
                        "City": "MILANO",
                        "PostalCode": 20154,
                        "CountryCode": "IT"
                    }
                },
                "Recipient": {
                    "Contact": {
                        "PersonName": "Tester 2",
                        "CompanyName": "Acme Inc",
                        "PhoneNumber": 88347346643,
                        "EmailAddress": "jackie.chan@eei.com"
                    },
                    "Address": {
                        "StreetLines": "500 Hunt Valley Road",
                        "City": "New Kensington PA",
                        "StateOrProvinceCode": "PA",
                        "PostalCode": 15068,
                        "CountryCode": "US"
                    }
                }
            },
            "Packages": {
                "RequestedPackages": [
                    {
                        "@number": "1",
                        "Weight": 2,
                        "Dimensions": {
                            "Length": 1,
                            "Width": 2,
                            "Height": 3
                        },
                        "CustomerReferences": "Piece 1"
                    }
                ]
            }
        }
    }
}
```

```json
{
	"PickUpRequest": {
		"PickUpShipment": {
			"ShipmentInfo": {
				"ServiceType": "U",
				"Billing": {
					"ShipperAccountNumber": "123456789",
					"ShippingPaymentType": "S"
				},
				"UnitOfMeasurement": "SI"
			},
			"PickupTimestamp": "2018-01-26T12:59:00 GMT+01:00",
			"InternationalDetail": {
				"Commodities": {
					"NumberOfPieces": "1",
					"Description": "Computer Parts"
				}
			},
			"Ship": {
				"Shipper": {
					"Contact": {
						"PersonName": "Topaz",
						"CompanyName": "DHLRequest Express",
						"PhoneNumber": "+31 6 53464291",
						"EmailAddress": "Topaz.Test@dhl.com",
						"MobilePhoneNumber": "+31 6 53464291"
					},
					"Address": {
						"StreetLines": "GloWS",
						"City": "Eindhoven",
						"PostalCode": "5657 ES",
						"CountryCode": "NL"
					}
				},
				"Recipient": {
					"Contact": {
						"PersonName": "Jack Jones",
						"CompanyName": "J and J Company",
						"PhoneNumber": "+44 25 77884444",
						"EmailAddress": "jack@jjcompany.com",
						"MobilePhoneNumber": "+44 5 88648666"
					},
					"Address": {
						"StreetLines": "Penny lane",
						"City": "Liverpool",
						"PostalCode": "AA21 9AA",
						"CountryCode": "GB"
					}
				}
			},
			"Packages": {
				"RequestedPackages": {
					"@number": "1",
					"Weight": "12.0",
					"Dimensions": {
						"Length": "70",
						"Width": "21",
						"Height": "44"
					},
					"CustomerReferences": "My-PU-Call-1"
				}
			}
		}
	}
}
```

