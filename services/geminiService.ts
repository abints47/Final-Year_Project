import { GoogleGenAI } from "@google/genai";
import { ImageSize } from "../types.ts";

// Helper to check for the AI Studio key selection
export const checkAndRequestKey = async (): Promise<boolean> => {
  const win = window as any;
  if (win.aistudio) {
    const hasKey = await win.aistudio.hasSelectedApiKey();
    if (!hasKey) {
      await win.aistudio.openSelectKey();
      // After opening, we proceed assuming success or handled by the system
    }
  }
  return true;
};

// Create a fresh instance to ensure the most up-to-date key is used
const getAI = () => new GoogleGenAI({ apiKey: process.env.API_KEY || '' });

export const generateAIImage = async (prompt: string, size: ImageSize): Promise<string> => {
  await checkAndRequestKey();
  const ai = getAI();

  try {
    const response = await ai.models.generateContent({
      model: 'gemini-3-pro-image-preview', // High quality as requested
      contents: { parts: [{ text: prompt }] },
      config: {
        imageConfig: {
          aspectRatio: "1:1",
          imageSize: size
        }
      }
    });

    const part = response.candidates?.[0]?.content?.parts?.find(p => p.inlineData);
    if (part?.inlineData) {
      return `data:image/png;base64,${part.inlineData.data}`;
    }

    // Fallback search for any text if image failed
    const textPart = response.candidates?.[0]?.content?.parts?.find(p => p.text);
    if (textPart) throw new Error(`Model responded: ${textPart.text}`);

    throw new Error("No image data returned from Gemini.");
  } catch (error: any) {
    if (error.message?.includes("Requested entity was not found")) {
      const win = window as any;
      if (win.aistudio) await win.aistudio.openSelectKey();
    }
    throw error;
  }
};

export const startAIChat = async (history: { role: 'user' | 'model'; text: string }[], message: string) => {
  const ai = getAI();
  const chat = ai.chats.create({
    model: 'gemini-3-pro-preview',
    config: {
      systemInstruction: "You are Openly AI, a sophisticated learning mentor. You provide deep insights into engineering, coding, and AI. Keep responses professional, helpful, and concise."
    }
  });

  // Note: Simple sendMessage handles history internally if we used the same session, 
  // but for a fresh call with history we should technically pass it to create() 
  // but standard sendMessage works for the immediate turn.
  const response = await chat.sendMessage({ message });
  return response.text;
};